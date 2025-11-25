<?php
require 'functions.php';
secure();

//only admins can delete companies
if (!is_admin()) {
    set_message('Access denied. Admin privileges required.', 'danger');
    header('Location: companies.php');
    exit;
}

require 'reusable/conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Company</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<?php
function redirectCompany(string $message, string $class = 'danger'): void {
    set_message($message, $class);
    header('Location: companies.php');
    exit;
}

$companyId = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $companyId = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $companyId = (int) $_GET['id'];
}

if ($companyId <= 0) {
    redirectCompany('Invalid company id supplied.');
}

$dependencyStmt = mysqli_prepare($conn, 'SELECT COUNT(*) FROM jobs WHERE company_id = ?');

if (!$dependencyStmt) {
    redirectCompany('Unable to prepare dependency check.');
}

mysqli_stmt_bind_param($dependencyStmt, 'i', $companyId);
mysqli_stmt_execute($dependencyStmt);
mysqli_stmt_bind_result($dependencyStmt, $jobCount);
mysqli_stmt_fetch($dependencyStmt);
mysqli_stmt_close($dependencyStmt);

if ($jobCount > 0) {
    redirectCompany('Delete or reassign the jobs linked to this company before removing it.');
}

$deleteStmt = mysqli_prepare($conn, 'DELETE FROM companies WHERE id = ?');

if (!$deleteStmt) {
    redirectCompany('Unable to prepare delete statement.');
}

mysqli_stmt_bind_param($deleteStmt, 'i', $companyId);
mysqli_stmt_execute($deleteStmt);

if (mysqli_stmt_errno($deleteStmt)) {
    $error = mysqli_stmt_error($deleteStmt);
    mysqli_stmt_close($deleteStmt);
    redirectCompany('Unable to delete company: ' . $error);
}

if (mysqli_stmt_affected_rows($deleteStmt) === 0) {
    mysqli_stmt_close($deleteStmt);
    redirectCompany('Company not found or already removed.', 'warning');
}

mysqli_stmt_close($deleteStmt);
redirectCompany('Company deleted successfully.', 'success');
?>
</body>
</html>
