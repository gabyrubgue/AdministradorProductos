<?php
require '../../config/db.php';

$id = $_GET['id'];

$producto = $pdo->prepare("SELECT estado FROM productos WHERE id=?");
$producto->execute([$id]);
$estado = $producto->fetchColumn();

$nuevo_estado = $estado === 'activo' ? 'inactivo' : 'activo';

$pdo->prepare("UPDATE productos SET estado=? WHERE id=?")
    ->execute([$nuevo_estado, $id]);

header("Location: index.php");
