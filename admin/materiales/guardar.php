<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    INSERT INTO materiales (nombre, descripcion, costo_base)
    VALUES (?,?,?)
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['descripcion'],
    $_POST['costo_base']
]);

header("Location: index.php");

