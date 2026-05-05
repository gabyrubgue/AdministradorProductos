<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    INSERT INTO tallas (nombre, tipo)
    VALUES (?,?)
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['tipo']
]);

header("Location: index.php");
