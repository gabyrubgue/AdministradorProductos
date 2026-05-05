<?php
require '../../config/db.php';

$id = $_POST['id'];

/* ===============================
   VARIANTES
================================ */
/* ===============================
   VARIANTES
================================ */
$tiene_variantes = isset($_POST['tiene_variantes'])
    ? (int)$_POST['tiene_variantes']
    : 0;

/* ===============================
   IMAGEN (opcional)
================================ */
$imagenSQL = "";
$paramsImagen = [];

if (!empty($_FILES['imagen']['name'])) {
    $imagen = time() . '_' . $_FILES['imagen']['name'];
    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/$imagen"
    );
    $imagenSQL = ", imagen = ?";
    $paramsImagen[] = $imagen;
}

/* ===============================
   UPDATE PRODUCTO
================================ */
$sql = "
UPDATE productos SET
    nombre = ?,
    categoria_id = ?,
    descripcion = ?,
    stock = ?,
    tiene_variantes = ?
    $imagenSQL
WHERE id = ?
";

$params = [
    $_POST['nombre'],
    $_POST['categoria_id'],
    $_POST['descripcion'],
    $_POST['stock'],
    $tiene_variantes
];

$params = array_merge($params, $paramsImagen);
$params[] = $id;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

/* ===============================
   TALLAS (solo si NO variantes)
================================ */
$pdo->prepare("
    DELETE FROM producto_tallas
    WHERE producto_id = ?
")->execute([$id]);

if (!empty($_POST['tallas_producto'])) {

    $stmtTalla = $pdo->prepare("
        INSERT INTO producto_tallas (producto_id, talla_id)
        VALUES (?,?)
    ");

    // Si es una sola talla
    if (!is_array($_POST['tallas_producto'])) {
        $stmtTalla->execute([$id, $_POST['tallas_producto']]);
    } 
    // Si son varias tallas
    else {
        foreach ($_POST['tallas_producto'] as $talla_id) {
            $stmtTalla->execute([$id, $talla_id]);
        }
    }
}




/* ===============================
   CARACTERÍSTICAS
================================ */
$pdo->prepare("
    DELETE FROM producto_caracteristicas
    WHERE producto_id=?
")->execute([$id]);

if (!empty($_POST['caracteristicas'])) {
    $stmtCar = $pdo->prepare("
        INSERT INTO producto_caracteristicas
        (producto_id, caracteristica_id, valor)
        VALUES (?,?,?)
    ");

    foreach ($_POST['caracteristicas'] as $caracteristica_id => $valor) {

        if ($valor === '' || $valor === null) {
            continue;
        }

        if ($valor === 'on') {
            $valor = 1;
        }

        $stmtCar->execute([
            $id,
            $caracteristica_id,
            $valor
        ]);
    }
}

$pdo->prepare("
    DELETE FROM producto_materiales WHERE producto_id = ?
")->execute([$id]);

$pdo->prepare("
    DELETE FROM producto_colores WHERE producto_id = ?
")->execute([$id]);

if (!empty($_POST['materiales'])) {
    $stmtMat = $pdo->prepare("
        INSERT INTO producto_materiales (producto_id, material_id)
        VALUES (?, ?)
    ");

    foreach ($_POST['materiales'] as $material_id) {
        $stmtMat->execute([$id, $material_id]);
    }
}
if (!empty($_POST['colores'])) {
    $stmtCol = $pdo->prepare("
        INSERT INTO producto_colores (producto_id, color_id)
        VALUES (?, ?)
    ");

    foreach ($_POST['colores'] as $color_id) {
        $stmtCol->execute([$id, $color_id]);
    }
}


/* ===============================
   REDIRECCIÓN
================================ */
header("Location: index.php");
exit;
