<?php
require '../../config/db.php';

/* =========================
   VALIDAR
========================= */
if (
    empty($_POST['producto_id']) ||
    empty($_POST['stock']) ||
    empty($_POST['talla_id']) ||
    empty($_POST['color_id'])
) {
    die('Datos incompletos');
}

$producto_id = (int) $_POST['producto_id'];
$stock       = (int) $_POST['stock'];
$talla_id    = (int) $_POST['talla_id'];
$color_id    = (int) $_POST['color_id'];

/* =========================
   PRECIO VARIANTE
========================= */
if (isset($_POST['precio']) && $_POST['precio'] !== '') {
    $precio = (float) $_POST['precio'];
} else {
    $precio = (float) $_POST['precio_base'];
}

/* =========================
   IMAGEN
========================= */
$imagen = null;
if (!empty($_FILES['imagen']['name'])) {
    $imagen = time() . '_' . basename($_FILES['imagen']['name']);
    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/$imagen"
    );
}

/* =========================
   INSERT VARIANTE
========================= */
$stmt = $pdo->prepare("
    INSERT INTO producto_variantes
    (producto_id, talla_id, color_id, precio, stock, imagen)
    VALUES (?,?,?,?,?,?)
");

$stmt->execute([
    $producto_id,
    $talla_id,
    $color_id,
    $precio,
    $stock,
    $imagen
]);

$variante_id = $pdo->lastInsertId();

/* =========================
   ATRIBUTOS
========================= */
if (!empty($_POST['atributos'])) {
    $stmtAttr = $pdo->prepare("
        INSERT INTO variante_atributos
        (variante_id, atributo_id, valor_id)
        VALUES (?,?,?)
    ");

    foreach ($_POST['atributos'] as $atributo_id => $valor_id) {
        $stmtAttr->execute([
            $variante_id,
            (int) $atributo_id,
            (int) $valor_id
        ]);
    }
}

/* =========================
   REDIRECCIÓN
========================= */
header("Location: variantes.php?id=" . $producto_id);
exit;
