<?php
require 'functions.php';
secure();

//only admins can delete users
if (!is_admin()) {
    set_message('Access denied. Admin privileges required.', 'danger');
    header('Location: jobs.php');
    exit;
}

require 'reusable/conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<?php
function redirectWithMessage(string $message, string $class = 'danger'): void {
    set_message($message, $class);
    header('Location: users.php');
    exit;
}

$userId = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $userId = (int) $_GET['id'];
}

if ($userId <= 0) {
    redirectWithMessage('Invalid user id supplied.');
}

if (isset($_SESSION['id']) && (int) $_SESSION['id'] === $userId) {
    redirectWithMessage('You cannot delete the account currently in use.');
}

$dependencyStmt = mysqli_prepare($conn, 'SELECT COUNT(*) FROM jobs WHERE user_id = ?');

if (!$dependencyStmt) {
    redirectWithMessage('Unable to prepare dependency check.');
}

mysqli_stmt_bind_param($dependencyStmt, 'i', $userId);
mysqli_stmt_execute($dependencyStmt);
mysqli_stmt_bind_result($dependencyStmt, $jobCount);
mysqli_stmt_fetch($dependencyStmt);
mysqli_stmt_close($dependencyStmt);

if ($jobCount > 0) {
    redirectWithMessage('Delete or reassign the user\'s jobs before removing the record.');
}

$deleteStmt = mysqli_prepare($conn, 'DELETE FROM users WHERE id = ?');

if (!$deleteStmt) {
    redirectWithMessage('Unable to prepare delete statement.');
}

mysqli_stmt_bind_param($deleteStmt, 'i', $userId);
mysqli_stmt_execute($deleteStmt);

if (mysqli_stmt_errno($deleteStmt)) {
    $error = mysqli_stmt_error($deleteStmt);
    mysqli_stmt_close($deleteStmt);
    redirectWithMessage('Unable to delete user: ' . $error);
}

if (mysqli_stmt_affected_rows($deleteStmt) === 0) {
    mysqli_stmt_close($deleteStmt);
    redirectWithMessage('User not found or already removed.', 'warning');
}

mysqli_stmt_close($deleteStmt);
redirectWithMessage('User deleted successfully.', 'success');
?>
</body>
</html>
