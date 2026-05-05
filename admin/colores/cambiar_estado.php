<?php
require '../../config/db.php';

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    UPDATE colores
    SET estado = IF(estado = 'activo', 'inactivo', 'activo')
    WHERE id = ?
");

$stmt->execute([$id]);

header("Location: index.php");
exit;
