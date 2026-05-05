<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    UPDATE materiales
    SET nombre=?, descripcion=?, costo_base=?, estado=?
    WHERE id=?
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['descripcion'],
    $_POST['costo_base'],
    $_POST['estado'],
    $_POST['id']
]);

header("Location: index.php");
