<?php
include 'functions.php';
secure();

//only admins can access this page
if (!is_admin()) {
    set_message('Access denied. Admin privileges required.', 'danger');
    header('Location: jobs.php');
    exit;
}

require 'reusable/conn.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($username) || empty($email) || empty($password)) {
        $error_message = 'All fields are required.';
    } else {
        //checking if username or email already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "' OR email = '" . mysqli_real_escape_string($conn, $email) . "'");
        if (mysqli_num_rows($check) > 0) {
            $error_message = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (username, email, password) VALUES ('" . mysqli_real_escape_string($conn, $username) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . $hashed_password . "')";
            if (mysqli_query($conn, $insert)) {
                set_message('User added successfully!', 'success');
                header('Location: users.php');
                exit;
            } else {
                $error_message = 'Error adding user: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <?php include 'reusable/nav.php'; ?>
  <div class="main-content">
    <div class="container-fluid">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6">
            <h1 class="display-4 mt-5 mb-4 text-center">Add New User</h1>
            <?php if ($error_message): ?>
              <div class="alert alert-danger"> <?= htmlspecialchars($error_message) ?> </div>
            <?php endif; ?>
            <form action="addUser.php" method="POST" class="p-4 rounded bg-white shadow-sm">
              <div class="mb-3">
                <label class="form-label">Username *</label>
                <input type="text" class="form-control" name="username" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password *</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <button type="submit" class="btn hero-btn d-block mx-auto" style="min-width: 200px;">Add User</button>
              <a href="users.php" class="btn btn-cancel mt-3 d-block mx-auto" style="max-width: 200px;">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
