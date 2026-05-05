<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    UPDATE categorias
    SET nombre=?, parent_id=?, estado=?
    WHERE id=?
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['parent_id'] ?: null,
    $_POST['estado'],
    $_POST['id']
]);

header("Location: index.php");
