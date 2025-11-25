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
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
  <?php include('reusable/nav.php'); ?>

  <div class="main-content">
    <div class="container">
      <div class="row align-items-center mb-3">
        <div class="col">
          <h1 class="display-4 mt-5 mb-5 text-break text-wrap"><?= is_admin() ? 'All Jobs (Admin View)' : 'My Jobs' ?></h1>
        </div>
        <div class="col-auto d-flex align-items-center justify-content-end gap-2 flex-wrap">
          <?php if (is_admin()): ?>
            <form method="GET" action="jobs.php" class="d-inline-block" style="vertical-align: middle;">
              <select name="user_id" class="form-select" style="height:48px; min-width:180px; font-size:1.1rem; font-weight:600; border-radius:8px;" onchange="this.form.submit()">
                <option value="">All Users</option>
                <?php
                  $users = mysqli_query($conn, "SELECT id, username, email FROM users ORDER BY username");
                  while ($user = mysqli_fetch_assoc($users)) {
                    if ($user['email'] === 'admin@example.com') continue;
                    $selected = (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : '';
                    echo '<option value="' . $user['id'] . '" ' . $selected . '>' . htmlspecialchars($user['username']) . '</option>';
                  }
                ?>
              </select>
            </form>
          <?php else: ?>
            <form method="GET" action="jobs.php" class="d-inline-block" style="vertical-align: middle;">
              <select name="status" class="form-select" style="height:48px; min-width:180px; font-size:1.1rem; font-weight:600; border-radius:8px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="Applied" <?= (isset($_GET['status']) && $_GET['status'] == 'Applied') ? 'selected' : '' ?>>Applied</option>
                <option value="Interview" <?= (isset($_GET['status']) && $_GET['status'] == 'Interview') ? 'selected' : '' ?>>Interview</option>
                <option value="Offer" <?= (isset($_GET['status']) && $_GET['status'] == 'Offer') ? 'selected' : '' ?>>Offer</option>
                <option value="Rejected" <?= (isset($_GET['status']) && $_GET['status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
              </select>
            </form>
          <?php endif; ?>
          <a href="addJob.php" class="btn hero-btn mb-4" style="min-width: 180px; height: 48px; display: inline-flex; align-items: center; justify-content: center;">Add Job</a>
        </div>
      </div>
    </div>

    <?php
    if (is_admin()) {
        $userFilter = isset($_GET['user_id']) && $_GET['user_id'] !== '' ? (int)$_GET['user_id'] : null;
        $query = "SELECT jobs.*, companies.name AS companyName, users.username 
                  FROM jobs
                  LEFT JOIN companies ON jobs.company_id = companies.id
                  LEFT JOIN users ON jobs.user_id = users.id";
        if ($userFilter) {
            $query .= " WHERE jobs.user_id = $userFilter";
        }
        $query .= " ORDER BY jobs.date_applied DESC";
    } else {
        $statusFilter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
        $query = "SELECT jobs.*, companies.name AS companyName, users.username 
                  FROM jobs
                  LEFT JOIN companies ON jobs.company_id = companies.id
                  LEFT JOIN users ON jobs.user_id = users.id
                  WHERE jobs.user_id = " . $_SESSION['id'];
        if ($statusFilter) {
            $query .= " AND jobs.status = '" . mysqli_real_escape_string($conn, $statusFilter) . "'";
        }
    }
    $jobs = mysqli_query($conn, $query);
    ?>

    <div class="container">
      <div class="row">
        <div class="col">
          <?php get_message(); ?>
        </div>
      </div>
      <div class="row">
        <?php foreach($jobs as $job) { ?>
          <div class="col-12 mb-4">
            <div class="card w-100">
              <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="job-info">
                  <h5 class="card-title text-break text-wrap mb-2"><?= $job['title'] ?></h5>
                  <p class="card-text mb-1">Company: <?= $job['companyName'] ?></p>
                  <?php if (is_admin()): ?>
                    <p class="card-text mb-1">User: <?= $job['username'] ?></p>
                  <?php endif; ?>
                  <span class="badge 
                    <?php
                      if ($job['status'] === 'Applied') echo 'bg-success';
                      elseif ($job['status'] === 'Rejected') echo 'bg-danger';
                      elseif ($job['status'] === 'Offer') echo 'bg-success bg-opacity-50';
                      else echo 'bg-secondary';
                    ?> me-2 mb-1">
                    <?= $job['status'] ?>
                  </span>
                  <span class="badge date-badge mb-1"><?= $job['date_applied'] ?></span>
                </div>
                <div class="job-actions d-flex gap-2 mt-3 mt-md-0 ms-md-auto">
                  <?php if (is_admin() || $job['user_id'] == $_SESSION['id']): ?>
                    <form action="updateJob.php">
                      <input type="hidden" name="id" value="<?= $job['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-dark">Update</button>
                    </form>
                    <form action="deleteJob.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this job?');">
                      <input type="hidden" name="id" value="<?= $job['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted">View only</span>
                  <?php endif; ?>
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
