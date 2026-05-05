<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    UPDATE tallas
    SET nombre=?, tipo=?, estado=?
    WHERE id=?
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['tipo'],
    $_POST['estado'],
    $_POST['id']
]);

header("Location: index.php");
