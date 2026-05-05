<?php
require_once '../../config/db.php';

$stmt = $pdo->prepare("
    INSERT INTO colores (nombre, codigo_hex)
    VALUES (?,?)
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['codigo_hex']
]);

header("Location: index.php");
