<?php

require_once '../../config/db.php';

$pdo->prepare("DELETE FROM colores WHERE id=?")
    ->execute([$_GET['id']]);

header("Location: index.php");

