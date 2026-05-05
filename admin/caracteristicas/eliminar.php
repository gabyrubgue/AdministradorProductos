<?php
require_once '../../config/db.php';

$pdo->prepare("DELETE FROM caracteristicas WHERE id=?")
    ->execute([$_GET['id']]);

header("Location: index.php");

