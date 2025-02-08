<?php
// Database configuration
$host = 'localhost'; // Database host
$db = 'copilot_clone'; // Database name
$user = 'root'; // MySQL username
$pass = 'your_new_password'; // Replace with your actual password
$charset = 'utf8mb4';

// DSN (Data Source Name) for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Attempt to connect to the database
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Database connected successfully!";
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection error: " . $e->getMessage());
}
?>
