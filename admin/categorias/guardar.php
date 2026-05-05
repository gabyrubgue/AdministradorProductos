<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    INSERT INTO categorias (nombre, parent_id)
    VALUES (?,?)
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['parent_id'] ?: null
]);

header("Location: index.php");
