<?php
require '../../config/db.php';

$id = (int)$_GET['id'];
$producto_id = (int)$_GET['p'];

/* ===============================
   IMAGEN
================================ */
$stmt = $pdo->prepare("
    SELECT imagen 
    FROM producto_variantes 
    WHERE id=?
");
$stmt->execute([$id]);
$img = $stmt->fetch();

if ($img && !empty($img['imagen'])) {
    $ruta = "../../uploads/" . $img['imagen'];
    if (file_exists($ruta)) {
        unlink($ruta);
    }
}

/* ===============================
   RELACIONES
================================ */
$pdo->prepare("
    DELETE FROM variante_atributos 
    WHERE variante_id=?
")->execute([$id]);

/* ===============================
   VARIANTE
================================ */
$pdo->prepare("
    DELETE FROM producto_variantes 
    WHERE id=?
")->execute([$id]);

/* ===============================
   REDIRECCIÓN
================================ */
header("Location: variantes.php?id=$producto_id");
exit;
