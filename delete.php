<?php
require_once 'db.php';
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = (int)$_GET['id'];
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
$stmt->execute([$id]);
header('Location: index.php');
exit;
