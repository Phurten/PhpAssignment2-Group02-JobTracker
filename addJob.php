<?php
include('functions.php');
include('reusable/conn.php');

$companies = mysqli_query($conn, "SELECT * FROM companies");
$users = mysqli_query($conn, "SELECT * FROM users");

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id     = $_POST['user_id'];
    $company_id  = $_POST['company_id'];
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location    = trim($_POST['location']);
    $status      = $_POST['status'];
    $date_applied = $_POST['date_applied'];

    if (empty($user_id) || empty($company_id) || empty($title) || empty($description) || empty($location) || empty($status) || empty($date_applied)) {
        $error_message = 'Please fill all fields!';
    } else {
        $query = "INSERT INTO jobs (user_id, company_id, title, description, location, status, date_applied)
                  VALUES ('$user_id', '$company_id', '$title', '$description', '$location', '$status', '$date_applied')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            set_message('Job added successfully!', 'success');
            header('Location: jobs.php');
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}
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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="jobs.php">Job Tracker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col">
        <h1 class="display-4 mt-5 mb-5">Add Job</h1>
      </div>
    </div>
  </div>
</div>

<?php if($error_message != ''): ?>
  <div class="container">
    <div class="alert alert-danger"><?= $error_message ?></div>
  </div>
<?php endif; ?>

<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col-md-6">

        <form action="addJob.php" method="POST">

          <div class="mb-3">
            <label for="user_id" class="form-label">Select User</label>
            <select name="user_id" required class="form-control">
              <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="company_id" class="form-label">Select Company</label>
            <select name="company_id" required class="form-control">
              <?php while ($company = mysqli_fetch_assoc($companies)) : ?>
                <option value="<?= $company['id'] ?>"><?= $company['name'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Job Title</label>
            <input type="text" class="form-control" name="title">
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" name="location">
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
              <option value="Applied">Applied</option>
              <option value="Interview">Interview</option>
              <option value="Offer">Offer</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Date Applied</label>
            <input type="date" class="form-control" name="date_applied">
          </div>

          <button type="submit" class="btn btn-primary">Add Job</button>

        </form>

      </div>
    </div>
  </div>
</div>

</body>
</html>
