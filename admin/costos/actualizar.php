<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    UPDATE costos
    SET nombre = ?, tipo = ?, precio = ?, estado = ?
    WHERE id = ?
");

$stmt->execute([
    trim($_POST['nombre']),
    $_POST['tipo'],
    (int) $_POST['precio'],
    $_POST['estado'],
    (int) $_POST['id']
]);

header("Location: index.php");
exit;
