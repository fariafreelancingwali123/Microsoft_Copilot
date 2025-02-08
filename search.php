<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = '%' . $_POST['query'] . '%';
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE content LIKE ? AND user_id = ?");
    $stmt->execute([$query, $_SESSION['user_id']]);
    $search_results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Search for Posts</h1>
    </header>
    <div class="container">
        <form method="POST" class="search-bar">
            <input type="text" name="query" placeholder="Search..." required>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($search_results)): ?>
            <ul>
                <?php foreach ($search_results as $result): ?>
                    <li><?= htmlspecialchars($result['content']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2025 Your Website</p>
    </footer>
</body>
</html>
