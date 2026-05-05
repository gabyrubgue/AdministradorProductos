<?php
require '../../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

/* ===============================
   IMAGEN PRINCIPAL DEL PRODUCTO
================================ */
$producto = $pdo->prepare("SELECT imagen FROM productos WHERE id=?");
$producto->execute([$id]);
$producto = $producto->fetch();

if ($producto && $producto['imagen']) {
    @unlink("../../uploads/" . $producto['imagen']);
}

/* ===============================
   VARIANTES DEL PRODUCTO
================================ */
$variantes = $pdo->prepare("
    SELECT id, imagen
    FROM producto_variantes
    WHERE producto_id=?
");
$variantes->execute([$id]);
$variantes = $variantes->fetchAll();

foreach ($variantes as $v) {

    // Imagen variante
    if ($v['imagen']) {
        @unlink("../../uploads/" . $v['imagen']);
    }

    // Atributos de la variante
    $pdo->prepare("
        DELETE FROM variante_atributos
        WHERE variante_id=?
    ")->execute([$v['id']]);

    // Variante
    $pdo->prepare("
        DELETE FROM producto_variantes
        WHERE id=?
    ")->execute([$v['id']]);
}

/* ===============================
   RELACIONES DEL PRODUCTO
================================ */

// Tallas del producto
$pdo->prepare("
    DELETE FROM producto_tallas
    WHERE producto_id=?
")->execute([$id]);

// Características
$pdo->prepare("
    DELETE FROM producto_caracteristicas
    WHERE producto_id=?
")->execute([$id]);

// Materiales
$pdo->prepare("
    DELETE FROM producto_materiales
    WHERE producto_id=?
")->execute([$id]);

// Colores
$pdo->prepare("
    DELETE FROM producto_colores
    WHERE producto_id=?
")->execute([$id]);

/* ===============================
   PRODUCTO
================================ */
$pdo->prepare("
    DELETE FROM productos
    WHERE id=?
")->execute([$id]);

/* ===============================
   REDIRECCIÓN
================================ */
header("Location: index.php");
exit;
