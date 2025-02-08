<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handling task addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task_name = $_POST['task'];
    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task_name, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $task_name]);
        header("Location: tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "Error adding task: " . $e->getMessage();
    }
}

// Fetch tasks from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error fetching tasks: " . $e->getMessage();
}

// Handling task completion
if (isset($_GET['complete']) && isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET completed = 1 WHERE task_id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        header("Location: tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "Error completing task: " . $e->getMessage();
    }
}

// Handling task deletion
if (isset($_GET['delete']) && isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        header("Location: tasks.php");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting task: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tasks</title>
    <style>
        /* Style your tasks page here */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f2f1;
            color: #323130;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #323130;
        }

        .task-input {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .task-input input {
            flex: 1;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #e1dfdd;
            border-radius: 5px;
        }

        .task-input button {
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .task-input button:hover {
            background-color: #005a9e;
        }

        .task-list {
            margin-top: 20px;
        }

        .task-list li {
            background: #f3f2f1;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .task-list li .actions button {
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .task-list li .actions button:hover {
            background-color: #005a9e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Tasks</h1>

        <!-- Task Input Form -->
        <form method="POST" class="task-input">
            <input type="text" name="task" placeholder="Add a new task..." required>
            <button type="submit">Add Task</button>
        </form>

        <!-- Task List -->
        <ul class="task-list">
            <?php if ($tasks): ?>
                <?php foreach ($tasks as $task): ?>
                    <li>
                        <span>
                            <?php echo htmlspecialchars($task['task_name']); ?>
                            <?php if ($task['completed'] == 1): ?>
                                <span style="color: green;">(Completed)</span>
                            <?php endif; ?>
                        </span>
                        <div class="actions">
                            <?php if ($task['completed'] == 0): ?>
                                <a href="tasks.php?complete=1&task_id=<?php echo $task['task_id']; ?>">
                                    <button>Complete</button>
                                </a>
                            <?php endif; ?>
                            <a href="tasks.php?delete=1&task_id=<?php echo $task['task_id']; ?>">
                                <button>Delete</button>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No tasks found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
