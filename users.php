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

$studentsQuery = "SELECT users.*, COUNT(jobs.id) AS job_count
                  FROM users
                  LEFT JOIN jobs ON jobs.user_id = users.id
                  GROUP BY users.id
                  ORDER BY users.username";

$students = mysqli_query($conn, $studentsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'reusable/nav.php'; ?>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <h1 class="display-4 mt-5 mb-3">Users Management</h1>
        <p class="text-muted">Manage job tracker users and remove inactive accounts.</p>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <?php get_message(); ?>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Tracked Jobs</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($students && mysqli_num_rows($students) > 0): ?>
                <?php while ($student = mysqli_fetch_assoc($students)): ?>
                  <tr>
                    <td><?= htmlspecialchars($student['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= (int) $student['job_count']; ?></td>
                    <td class="text-end">
                      <form action="deleteUser.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                        <input type="hidden" name="id" value="<?= (int) $student['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center text-muted">No users found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
