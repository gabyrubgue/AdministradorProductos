
<?php
require_once '../../config/db.php';
require '../layout/header.php';

$stmt = $pdo->prepare("
    UPDATE colores
    SET nombre=?, codigo_hex=?, estado=?
    WHERE id=?
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['codigo_hex'],
    $_POST['estado'],
    $_POST['id']
]);

header("Location: index.php");
