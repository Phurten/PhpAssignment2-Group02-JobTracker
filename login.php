<?php
session_start();
  include('reusable/conn.php');
  include('functions.php');

  if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = 'SELECT * 
          FROM users
          WHERE email = "' . $email . '"
          AND password = "' . $password . '"
          LIMIT 1';
    
    $result = mysqli_query($conn, $query);
    
    if(!$result) {
        set_message('Database error: ' . mysqli_error($conn), 'danger');
    } elseif(mysqli_num_rows($result)){
      $record = mysqli_fetch_assoc($result);
      $_SESSION['id'] = $record['id'];
      $_SESSION['username'] = $record['username'];
      $_SESSION['email'] = $record['email'];
      header('Location: jobs.php');
      die();
    } else{
      set_message('Invalid email or password. Please check your credentials.', 'danger');
      header('Location: login.php');
      die();
    }
  }

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Tracker - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
  <!-- Sidebar navbar intentionally omitted on login.php for correct layout -->
  <div class="container fluid">
    <div class="container">
      <div class="row">
        <div class="col text-center">
          <h3 class="mt-5 mb-5">Job Tracker Login</h3>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <?php get_message(); ?>
        </div>
      </div>
      <div class="row justify-content-center align-items-center" style="min-height: 20vh; min-width: 80vw;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
          <form method="POST" action="login.php">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" name="email" id="email">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary hero-btn" name="login">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>