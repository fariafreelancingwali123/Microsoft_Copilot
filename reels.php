<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM uploads WHERE user_id = ? AND file_name LIKE '%.mp4' LIMIT 10");
$stmt->execute([$user_id]);
$reels = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reels</title>
</head>
<body>
    <h1>Your Reels</h1>
    <div>
        <?php foreach ($reels as $reel): ?>
            <video src="<?= htmlspecialchars($reel['file_path']); ?>" controls width="200"></video>
        <?php endforeach; ?>
    </div>
</body>
</html>
