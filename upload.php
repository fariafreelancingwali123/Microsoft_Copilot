<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['file'];

    $upload_dir = 'uploads/';
    $file_name = basename($file['name']);
    $target_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $stmt = $pdo->prepare("INSERT INTO uploads (user_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $file_name, $target_path]);
        $message = "File uploaded successfully.";
    } else {
        $message = "Failed to upload the file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
    <h1>Upload File</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
</body>
</html>
