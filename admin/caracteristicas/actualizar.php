<?php
require '../../config/db.php';

$categoria_id = empty($_POST['categoria_id'])
    ? NULL
    : $_POST['categoria_id'];

$stmt = $pdo->prepare("
    UPDATE caracteristicas
    SET nombre=?, tipo=?, categoria_id=?
    WHERE id=?
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['tipo'],
    $categoria_id,
    $_POST['id']
]);

header("Location: index.php");
