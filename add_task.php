<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['task'])) {
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task_name) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_POST['task']]);
    header("Location: dashboard.php");
} catch(PDOException $e) {
    $_SESSION['error'] = "Failed to add task";
    header("Location: dashboard.php");
}
exit();
?>
