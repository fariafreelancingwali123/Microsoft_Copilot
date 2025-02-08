<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM uploads WHERE user_id = ? AND file_name LIKE '%.jpg' OR file_name LIKE '%.png'");
$stmt->execute([$user_id]);
$photos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photos</title>
</head>
<body>
    <h1>Your Photos</h1>
    <div>
        <?php foreach ($photos as $photo): ?>
            <img src="<?= htmlspecialchars($photo['file_path']); ?>" alt="<?= htmlspecialchars($photo['file_name']); ?>" width="200">
        <?php endforeach; ?>
    </div>
</body>
</html>
