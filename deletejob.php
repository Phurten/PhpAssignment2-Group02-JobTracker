<?php
require 'functions.php';
secure();
require 'reusable/conn.php';

$jobId = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $jobId = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $jobId = (int) $_GET['id'];
}

if ($jobId <= 0) {
    set_message('Invalid job id supplied.', 'danger');
    header('Location: jobs.php');
    exit;
}

$stmt = mysqli_prepare($conn, 'DELETE FROM jobs WHERE id = ?' . (is_admin() ? '' : ' AND user_id = ?'));

if (!$stmt) {
    set_message('Unable to prepare delete statement.', 'danger');
    header('Location: jobs.php');
    exit;
}

if (is_admin()) {
    mysqli_stmt_bind_param($stmt, 'i', $jobId);
} else {
    mysqli_stmt_bind_param($stmt, 'ii', $jobId, $_SESSION['id']);
}
mysqli_stmt_execute($stmt);

if (mysqli_stmt_errno($stmt)) {
    set_message('Unable to delete job: ' . mysqli_stmt_error($stmt), 'danger');
} elseif (mysqli_stmt_affected_rows($stmt) === 0) {
    set_message('Job not found, already deleted, or you do not have permission to delete this job.', 'warning');
} else {
    set_message('Job deleted successfully.', 'success');
    
    //removing companies that no longer have any associated jobs
    $cleanup_query = "DELETE FROM companies WHERE id NOT IN (SELECT DISTINCT company_id FROM jobs WHERE company_id IS NOT NULL)";
    mysqli_query($conn, $cleanup_query);
}

mysqli_stmt_close($stmt);
header('Location: jobs.php');
exit;
