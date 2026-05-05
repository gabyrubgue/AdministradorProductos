<?php
require '../../config/db.php';

$variante_id = $_POST['variante_id'];
$cantidad = $_POST['cantidad'];
$precio_venta = $_POST['precio_venta'];

/* Traer costos */
$var = $pdo->query("
    SELECT costo FROM producto_variantes WHERE id=$variante_id
")->fetch();

$costos = $pdo->query("
    SELECT IFNULL(SUM(valor),0) total
    FROM costo_asignaciones
    WHERE variante_id=$variante_id
")->fetch();

$costo_total = ($var['costo'] + $costos['total']) * $cantidad;
$total_venta = $precio_venta * $cantidad;
$ganancia = $total_venta - $costo_total;

/* Registrar venta */
$stmt = $pdo->prepare(
    "INSERT INTO ventas (cliente_id, total, ganancia)
     VALUES (?,?,?)"
);
$stmt->execute([
    $_POST['cliente_id'] ?: null,
    $total_venta,
    $ganancia
]);

$venta_id = $pdo->lastInsertId();

/* Detalle */
$pdo->prepare("
    INSERT INTO venta_detalle
    (venta_id, variante_id, precio_venta, costo_total, cantidad)
    VALUES (?,?,?,?,?)
")->execute([
    $venta_id,
    $variante_id,
    $precio_venta,
    $costo_total,
    $cantidad
]);

/* Descontar stock */
$pdo->query("
    UPDATE producto_variantes
    SET stock = stock - $cantidad
    WHERE id = $variante_id
");

header("Location: factura.php?id=$venta_id");
