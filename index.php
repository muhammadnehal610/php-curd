<?php
include("connectDB.php");

// Initialize variables
$title = $description = $error = $success = "";

// Process form submission and handle messages
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_task"])) {
        $title = trim($_POST["title"]);
        $description = trim($_POST["description"]);

        if (empty($title)) {
            $error = "Title is required.";
        } else {
            $stmt = $connectDB->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $description);

            if ($stmt->execute()) {
                $success = "Task added successfully!";
                $title = $description = ""; // Clear form fields
            } else {
                $error = "Error adding task: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif (isset($_POST["delete_task"])) {
        $id = intval($_POST["delete_id"]);
        $stmt = $connectDB->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $success = "Task deleted successfully!";
        } else {
            $error = "Error deleting task: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Retrieve tasks from the database
$result = $connectDB->query("SELECT * FROM tasks ORDER BY created_at DESC");

// Close the database connection
$connectDB->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }

        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .error {
            background-color: #ffebee;
            color: #c62828;
        }

        .success {
            background-color: #e8f5e9;
            color: #388e3c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Task Manager</h1>

        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="title" placeholder="Task Title" value="<?php echo htmlspecialchars($title); ?>"
                required>
            <textarea name="description"
                placeholder="Task Description"><?php echo htmlspecialchars($description); ?></textarea>
            <input type="submit" name="add_task" value="Add Task">
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                onsubmit="return confirm('Are you sure you want to delete this task?');">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <input type="submit" name="delete_task" value="Delete" class="delete-btn">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>
    </div>
</body>

</html>