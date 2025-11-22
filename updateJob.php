<?php
include('functions.php');
secure();
include("reusable/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (is_admin()) {
        //admin can edit any job
        $query = "SELECT * FROM jobs WHERE id = $id";
    } else {
        //regular users can only edit their own jobs
        $query = "SELECT * FROM jobs WHERE id = $id AND user_id = " . $_SESSION['id'];
    }
    
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 0) {
        set_message('Job not found or you do not have permission to edit this job.', 'danger');
        header('Location: jobs.php');
        exit;
    }
    
    $job = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $company_id = $_POST['company_id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $date_applied = $_POST['date_applied'];

    if (is_admin()) {
        //admin can update any job
        $query = "UPDATE jobs SET 
                    company_id = '$company_id',
                    title = '$title',
                    description = '$desc',
                    location = '$location',
                    status = '$status',
                    date_applied = '$date_applied'
                  WHERE id = $id";
    } else {
        //regular users can only update their own jobs
        $query = "UPDATE jobs SET 
                    company_id = '$company_id',
                    title = '$title',
                    description = '$desc',
                    location = '$location',
                    status = '$status',
                    date_applied = '$date_applied'
                  WHERE id = $id AND user_id = " . $_SESSION['id'];
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) > 0) {
        set_message('Job updated successfully!', 'success');
        header('Location: jobs.php');
        exit;
    } else {
        set_message('Failed to update job or you do not have permission.', 'danger');
    }
}

//companies for the dropdown
$companies = mysqli_query($conn, "SELECT * FROM companies");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('reusable/nav.php'); ?>

  <div class="container mt-5">
    <h1 class="display-5 mb-4"><?= is_admin() ? 'Update Job (Admin)' : 'Update My Job' ?></h1>

    <?php get_message(); ?>

    <form action="updateJob.php" method="POST">

      <input type="hidden" name="id" value="<?php echo $job['id']; ?>">

      <div class="mb-3">
        <label class="form-label">Company</label>
        <select class="form-control" name="company_id">
          <?php 
          mysqli_data_seek($companies, 0); // Reset pointer
          while ($c = mysqli_fetch_assoc($companies)) { ?>
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
               value="<?php echo htmlspecialchars($job['title']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" required><?php echo htmlspecialchars($job['description']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Location</label>
        <input type="text" class="form-control" name="location"
               value="<?php echo htmlspecialchars($job['location']); ?>" required>
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
               value="<?php echo $job['date_applied']; ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Update Job</button>
      <a href="jobs.php" class="btn btn-secondary">Cancel</a>
    </form>

  </div>
</body>
</html>