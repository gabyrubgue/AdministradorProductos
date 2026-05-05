<?php
require '../../config/db.php';

$categoria_id = empty($_POST['categoria_id'])
    ? NULL
    : $_POST['categoria_id'];

$stmt = $pdo->prepare("
    INSERT INTO caracteristicas (nombre, tipo, categoria_id)
    VALUES (?, ?, ?)
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['tipo'],
    $categoria_id
]);

header("Location: index.php");
