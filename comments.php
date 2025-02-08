<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['post_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $_SESSION['user_id'], $comment]);
}

$stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
</head>
<body>
    <h1>Comments</h1>
    <form method="POST">
        <textarea name="comment" placeholder="Write a comment..." required></textarea>
        <button type="submit">Comment</button>
    </form>
    <ul>
        <?php foreach ($comments as $comment): ?>
            <li><?= htmlspecialchars($comment['comment']); ?> - <small><?= $comment['created_at']; ?></small></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
