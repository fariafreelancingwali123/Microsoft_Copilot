<?php
// Database configuration
$host = 'localhost'; // Database host
$db = 'copilot_clone'; // Database name
$user = 'root'; // Database username
$pass = 'yourpassword'; // Replace with your MySQL root password
$charset = 'utf8mb4';

// Connect to the database
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Uncomment this to test database connectivity
    // echo "Database connected successfully!";
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Helper function to send JSON responses
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Get the request method and data
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestBody = json_decode(file_get_contents('php://input'), true);

// Handle API routes
if ($_SERVER['REQUEST_URI'] === '/signup' && $requestMethod === 'POST') {
    handleSignup($requestBody, $pdo);
} elseif ($_SERVER['REQUEST_URI'] === '/login' && $requestMethod === 'POST') {
    handleLogin($requestBody, $pdo);
} else {
    jsonResponse(['error' => 'Invalid route or method.'], 404);
}

// Signup function
function handleSignup($data, $pdo) {
    $username = $data['username'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$username || !$email || !$password) {
        jsonResponse(['error' => 'All fields are required.'], 400);
    }

    try {
        // Check if email already exists
        $query = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        if ($query->fetch()) {
            jsonResponse(['error' => 'Email already in use.'], 400);
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $insert->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
        ]);

        jsonResponse(['message' => 'Registration successful!'], 201);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Registration failed. Please try again.'], 500);
    }
}

// Login function
function handleLogin($data, $pdo) {
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$email || !$password) {
        jsonResponse(['error' => 'All fields are required.'], 400);
    }

    try {
        // Fetch the user by email
        $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch();

        if (!$user) {
            jsonResponse(['error' => 'User not found.'], 404);
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            jsonResponse(['error' => 'Invalid credentials.'], 401);
        }

        // Exclude the password from the response
        unset($user['password']);
        jsonResponse(['message' => 'Login successful!', 'user' => $user]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Login failed. Please try again.'], 500);
    }
}
?>
