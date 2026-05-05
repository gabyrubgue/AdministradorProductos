<?php
require '../../config/db.php';

/* ===============================
   DATOS BÁSICOS
================================ */
$tiene_variantes = isset($_POST['tiene_variantes'])
    ? (int)$_POST['tiene_variantes']
    : 0;

/* ===============================
   IMAGEN
================================ */
$imagen = 'default.png'; ;
if (!empty($_FILES['imagen']['name'])) {
    $imagen = time() . '_' . basename($_FILES['imagen']['name']);
    move_uploaded_file(
        $_FILES['imagen']['tmp_name'],
        "../../uploads/$imagen"
    );
}

/* ===============================
   INSERT PRODUCTO
================================ */
$stmt = $pdo->prepare("
    INSERT INTO productos
    (nombre, categoria_id, descripcion, precio, stock, tiene_variantes, imagen)
    VALUES (?,?,?,?,?,?,?)
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['categoria_id'],
    $_POST['descripcion'],
    $_POST['precio'],
    $_POST['stock'],
    $tiene_variantes,
    $imagen
]);

$producto_id = $pdo->lastInsertId();

/* ===============================
   TALLA (SOLO UNA Y SOLO SI NO HAY VARIANTES)
================================ */
if ($tiene_variantes === 0 && !empty($_POST['talla_producto'])) {
    $stmtTalla = $pdo->prepare("
        INSERT INTO producto_tallas (producto_id, talla_id)
        VALUES (?,?)
    ");

    $stmtTalla->execute([
        $producto_id,
        $_POST['talla_producto']
    ]);
}



/* ===============================
   CARACTERÍSTICAS
================================ */
if (!empty($_POST['caracteristicas'])) {
    $stmtCar = $pdo->prepare("
        INSERT INTO producto_caracteristicas
        (producto_id, caracteristica_id, valor)
        VALUES (?,?,?)
    ");

    foreach ($_POST['caracteristicas'] as $caracteristica_id => $valor) {

        // ❌ no guardar vacíos
        if ($valor === '' || $valor === null) {
            continue;
        }

        // ✔️ checkbox booleano
        if ($valor === '1') {
            $valor = 1;
        }

        $stmtCar->execute([
            $producto_id,
            $caracteristica_id,
            $valor
        ]);
    }
}


/* ===============================
   MATERIALES
================================ */
if (!empty($_POST['materiales'])) {
    $stmtMat = $pdo->prepare("
        INSERT INTO producto_materiales (producto_id, material_id)
        VALUES (?, ?)
    ");

    foreach ($_POST['materiales'] as $material_id) {
        $stmtMat->execute([
            $producto_id,
            $material_id
        ]);
    }
}

/* ===============================
   COLORES
================================ */
if (!empty($_POST['colores'])) {
    $stmtCol = $pdo->prepare("
        INSERT INTO producto_colores (producto_id, color_id)
        VALUES (?, ?)
    ");

    foreach ($_POST['colores'] as $color_id) {
        $stmtCol->execute([
            $producto_id,
            $color_id
        ]);
    }
}

/* ===============================
   REDIRECCIÓN
================================ */
header("Location: index.php");
exit;
