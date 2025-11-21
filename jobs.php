<?php 
include('functions.php');
secure();
require('reusable/conn.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('reusable/nav.php'); ?>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <h1 class="display-4 mt-5 mb-5">All Jobs</h1>
      </div>
    </div>
  </div>
</div>

<?php
$query = "SELECT jobs.*, companies.name AS companyName, users.username 
          FROM jobs
          LEFT JOIN companies ON jobs.company_id = companies.id
          LEFT JOIN users ON jobs.user_id = users.id";
$jobs = mysqli_query($conn, $query);
?>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <?php get_message(); ?>
      </div>
    </div>
    <div class="row">
      <?php foreach($jobs as $job) { ?>
        <div class="col-md-4 mt-2 mb-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?= $job['title'] ?></h5>
              <p class="card-text">Company: <?= $job['companyName'] ?></p>
              <p class="card-text">User: <?= $job['username'] ?></p>
              <span class="badge bg-secondary"><?= $job['status'] ?></span>
              <span class="badge bg-info"><?= $job['date_applied'] ?></span>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col">
                  <form action="updateJob.php">
                    <input type="hidden" name="id" value="<?= $job['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                  </form>
                </div>
                <div class="col text-end">
                  <form action="deleteJob.php" method="POST">
                    <input type="hidden" name="id" value="<?= $job['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

</body>
</html>
