<?php
require '../../config/db.php';


$precio = (int) $_POST['precio'];

$stmt = $pdo->prepare(
    "INSERT INTO costos (nombre, tipo, precio) VALUES (?,?,?)"
);

$stmt->execute([
    trim($_POST['nombre']),
    $_POST['tipo'],
    $precio
]);


header("Location: index.php");