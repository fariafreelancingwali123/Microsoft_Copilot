<?php
// Include database connection
require 'db.php';

header('Content-Type: application/json');

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if (!$email || !$password) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

try {
    // Check if user exists
    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->execute(['email' => $email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['error' => 'User not found.']);
        exit;
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo json_encode(['error' => 'Invalid credentials.']);
        exit;
    }

    // Exclude the password from the response
    unset($user['password']);

    echo json_encode([
        'message' => 'Login successful!',
        'user' => $user
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Login failed. Please try again.']);
    error_log('Login Error: ' . $e->getMessage());
}
