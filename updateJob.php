

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Job Tracker</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="jobs.php">All Jobs</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <h1 class="display-5 mb-4">Update Job</h1>

    <?php
      include("reusable/conn.php");

      if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
          $id = $_GET['id'];
          $query = "SELECT * FROM jobs WHERE id = $id";
          $result = mysqli_query($conn, $query);
          $job = mysqli_fetch_assoc($result);
      }

      
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $id = $_POST['id'];
          $user_id = $_POST['user_id'];
          $company_id = $_POST['company_id'];
          $title = $_POST['title'];
          $desc = $_POST['description'];
          $location = $_POST['location'];
          $status = $_POST['status'];
          $date_applied = $_POST['date_applied'];

          $query = "UPDATE jobs SET 
                      user_id = '$user_id',
                      company_id = '$company_id',
                      title = '$title',
                      description = '$desc',
                      location = '$location',
                      status = '$status',
                      date_applied = '$date_applied'
                    WHERE id = $id";

          $result = mysqli_query($conn, $query);

          if ($result) {
              echo "<div class='alert alert-success'>Job updated successfully!</div>";
              header("refresh:1; url=jobs.php");
          } else {
              echo "<div class='alert alert-danger'>Failed: " . mysqli_error($conn) . "</div>";
          }
      }

    
      $users = mysqli_query($conn, "SELECT * FROM users");
      $companies = mysqli_query($conn, "SELECT * FROM companies");
    ?>

    <form action="updateJob.php" method="POST">

      <input type="hidden" name="id" value="<?php echo $job['id']; ?>">

      <div class="mb-3">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id">
          <?php while ($u = mysqli_fetch_assoc($users)) { ?>
            <option value="<?php echo $u['id']; ?>" 
              <?php echo ($u['id'] == $job['user_id']) ? "selected" : ""; ?>>
              <?php echo $u['username']; ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Company</label>
        <select class="form-control" name="company_id">
          <?php while ($c = mysqli_fetch_assoc($companies)) { ?>
            <option value="<?php echo $c['id']; ?>"
              <?php echo ($c['id'] == $job['company_id']) ? "selected" : ""; ?>>
              <?php echo $c['name']; ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Job Title</label>
        <input type="text" class="form-control" name="title"
               value="<?php echo $job['title']; ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description"><?php echo $job['description']; ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" class="form-control" name="location"
               value="<?php echo $job['location']; ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select class="form-control" name="status">
          <option <?php echo ($job['status']=="Applied") ? "selected" : ""; ?>>Applied</option>
          <option <?php echo ($job['status']=="Interview") ? "selected" : ""; ?>>Interview</option>
          <option <?php echo ($job['status']=="Offer") ? "selected" : ""; ?>>Offer</option>
          <option <?php echo ($job['status']=="Rejected") ? "selected" : ""; ?>>Rejected</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Date Applied</label>
        <input type="date" class="form-control" name="date_applied"
               value="<?php echo $job['date_applied']; ?>">
      </div>

      <button type="submit" class="btn btn-primary">Update Job</button>
    </form>

  </div>
</body>
</html>
