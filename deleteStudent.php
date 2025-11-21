<?php
require 'functions.php';
secure();
require 'reusable/conn.php';

function redirectWithMessage(string $message, string $class = 'danger'): void {
    set_message($message, $class);
    header('Location: students.php');
    exit;
}

$studentId = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $studentId = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $studentId = (int) $_GET['id'];
}

if ($studentId <= 0) {
    redirectWithMessage('Invalid student id supplied.');
}

if (isset($_SESSION['id']) && (int) $_SESSION['id'] === $studentId) {
    redirectWithMessage('You cannot delete the account currently in use.');
}

$dependencyStmt = mysqli_prepare($conn, 'SELECT COUNT(*) FROM jobs WHERE user_id = ?');

if (!$dependencyStmt) {
    redirectWithMessage('Unable to prepare dependency check.');
}

mysqli_stmt_bind_param($dependencyStmt, 'i', $studentId);
mysqli_stmt_execute($dependencyStmt);
mysqli_stmt_bind_result($dependencyStmt, $jobCount);
mysqli_stmt_fetch($dependencyStmt);
mysqli_stmt_close($dependencyStmt);

if ($jobCount > 0) {
    redirectWithMessage('Delete or reassign the student\'s jobs before removing the record.');
}

$deleteStmt = mysqli_prepare($conn, 'DELETE FROM users WHERE id = ?');

if (!$deleteStmt) {
    redirectWithMessage('Unable to prepare delete statement.');
}

mysqli_stmt_bind_param($deleteStmt, 'i', $studentId);
mysqli_stmt_execute($deleteStmt);

if (mysqli_stmt_errno($deleteStmt)) {
    $error = mysqli_stmt_error($deleteStmt);
    mysqli_stmt_close($deleteStmt);
    redirectWithMessage('Unable to delete student: ' . $error);
}

if (mysqli_stmt_affected_rows($deleteStmt) === 0) {
    mysqli_stmt_close($deleteStmt);
    redirectWithMessage('Student not found or already removed.', 'warning');
}

mysqli_stmt_close($deleteStmt);
redirectWithMessage('Student deleted successfully.', 'success');
