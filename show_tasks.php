<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle task deletion
if (isset($_POST['delete_task']) && isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    
    $_SESSION['success'] = "Task deleted successfully!";
    header("Location: tasks.php");
    exit;
}

// Handle task status update
if (isset($_POST['toggle_status']) && isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("UPDATE tasks SET completed = NOT completed WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    
    header("Location: tasks.php");
    exit;
}

// Fetch tasks with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Tasks per page
$offset = ($page - 1) * $limit;

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$user_id, $limit, $offset]);
$tasks = $stmt->fetchAll();

// Get total task count for pagination
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_tasks = $stmt->fetchColumn();
$total_pages = ceil($total_tasks / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #292827;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .header {
            background-color: #fff;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-content {
            flex: 1;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
        }

        .task-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .task-meta {
            font-size: 0.875rem;
            color: #666;
        }

        .completed {
            text-decoration: line-through;
            color: #666;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }

        .btn-complete {
            background-color: #10B981;
            color: white;
        }

        .btn-complete:hover {
            background-color: #059669;
        }

        .btn-delete {
            background-color: #EF4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #DC2626;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #f3f9f4;
            color: #1f9d55;
            border: 1px solid #e3f1e4;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a {
            padding: 0.5rem 1rem;
            background: #fff;
            border-radius: 4px;
            text-decoration: none;
            color: #0078d4;
            transition: background-color 0.2s;
        }

        .pagination a:hover {
            background-color: #f0f2f5;
        }

        .pagination .active {
            background-color: #0078d4;
            color: white;
        }

        @media (max-width: 768px) {
            .task-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .task-actions {
                margin-top: 1rem;
                width: 100%;
            }

            .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Management</h1>
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']); ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($tasks)): ?>
            <p>No tasks found. Start by adding some tasks!</p>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <div class="task-content">
                        <div class="task-title <?= $task['completed'] ? 'completed' : '' ?>">
                            <?= htmlspecialchars($task['task_name']); ?>
                        </div>
                        <div class="task-meta">
                            Added on: <?= date('M j, Y g:i A', strtotime($task['created_at'])); ?>
                        </div>
                    </div>
                    <div class="task-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                            <button type="submit" name="toggle_status" class="btn btn-complete">
                                <?= $task['completed'] ? 'Mark Incomplete' : 'Mark Complete' ?>
                            </button>
                        </form>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                            <button type="submit" name="delete_task" class="btn btn-delete">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="<?= $page === $i ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
