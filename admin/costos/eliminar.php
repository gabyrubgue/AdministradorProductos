<?php
require_once '../../config/db.php';

$id = (int) $_GET['id'];

$pdo->prepare("DELETE FROM costos WHERE id = ?")
    ->execute([$id]);

header("Location: index.php");
exit;
