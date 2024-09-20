<?php
include('db.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $sql = "INSERT INTO tasks (user_id, title, description) VALUES ('$user_id', '$title', '$description')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];

    $sql = "UPDATE tasks SET is_completed=1 WHERE id='$task_id' AND user_id='$user_id'";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM tasks WHERE user_id='$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Task Management</h2>
    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required><br>
        <label>Description:</label>
        <textarea name="description"></textarea><br>
        <button type="submit">Add Task</button>
        <a href="logout.php">Logout</a>
    </form>
    <h3>Your Tasks</h3>
    <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo $row['title']; ?> - <?php echo $row['description']; ?>
                <?php if (!$row['is_completed']): ?>
                    <a href="?complete=<?php echo $row['id']; ?>">Mark as Complete</a>
                <?php else: ?>
                    <span>(Completed)</span>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>