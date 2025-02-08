<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM uploads WHERE user_id = ? AND file_name LIKE '%.mp4' OR file_name LIKE '%.mov'");
$stmt->execute([$user_id]);
$videos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos</title>
</head>
<body>
    <h1>Your Videos</h1>
    <div>
        <?php foreach ($videos as $video): ?>
            <video src="<?= htmlspecialchars($video['file_path']); ?>" controls width="400"></video>
        <?php endforeach; ?>
    </div>
</body>
</html>
