<?php
include('functions.php');
secure();
include("reusable/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (is_admin()) {
        // Admin can edit any job
        $query = "SELECT jobs.*, companies.name as company_name, companies.website as company_website, companies.industry as company_industry 
                  FROM jobs 
                  LEFT JOIN companies ON jobs.company_id = companies.id 
                  WHERE jobs.id = $id";
    } else {
        // Regular users can only edit their own jobs
        $query = "SELECT jobs.*, companies.name as company_name, companies.website as company_website, companies.industry as company_industry 
                  FROM jobs 
                  LEFT JOIN companies ON jobs.company_id = companies.id 
                  WHERE jobs.id = $id AND jobs.user_id = " . $_SESSION['id'];
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
    $company_name = trim($_POST['company_name']);
    $company_website = trim($_POST['company_website']);
    $company_industry = trim($_POST['company_industry']);
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $date_applied = $_POST['date_applied'];

    // First, handle company update/creation
    $company_id = null;
    
    // Check if company already exists with this name
    $check_company = "SELECT id FROM companies WHERE name = '" . mysqli_real_escape_string($conn, $company_name) . "'";
    $company_result = mysqli_query($conn, $check_company);
    
    if (mysqli_num_rows($company_result) > 0) {
        // Company exists, get its ID and update it
        $company_row = mysqli_fetch_assoc($company_result);
        $company_id = $company_row['id'];
        
        // Update existing company
        $update_company = "UPDATE companies SET 
                          website = '" . mysqli_real_escape_string($conn, $company_website) . "',
                          industry = '" . mysqli_real_escape_string($conn, $company_industry) . "'
                          WHERE id = $company_id";
        mysqli_query($conn, $update_company);
    } else {
        // Company doesn't exist, create new one
        $insert_company = "INSERT INTO companies (name, website, industry) VALUES (
            '" . mysqli_real_escape_string($conn, $company_name) . "', 
            '" . mysqli_real_escape_string($conn, $company_website) . "', 
            '" . mysqli_real_escape_string($conn, $company_industry) . "'
        )";
        
        if (mysqli_query($conn, $insert_company)) {
            $company_id = mysqli_insert_id($conn);
        }
    }

    if (is_admin()) {
        // Admin can update any job
        $query = "UPDATE jobs SET 
                    company_id = '$company_id',
                    title = '" . mysqli_real_escape_string($conn, $title) . "',
                    description = '" . mysqli_real_escape_string($conn, $desc) . "',
                    location = '" . mysqli_real_escape_string($conn, $location) . "',
                    status = '$status',
                    date_applied = '$date_applied'
                  WHERE id = $id";
    } else {
        // Regular users can only update their own jobs
        $query = "UPDATE jobs SET 
                    company_id = '$company_id',
                    title = '" . mysqli_real_escape_string($conn, $title) . "',
                    description = '" . mysqli_real_escape_string($conn, $desc) . "',
                    location = '" . mysqli_real_escape_string($conn, $location) . "',
                    status = '$status',
                    date_applied = '$date_applied'
                  WHERE id = $id AND user_id = " . $_SESSION['id'];
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) > 0) {
        set_message('Job and company updated successfully!', 'success');
        header('Location: jobs.php');
        exit;
    } else {
        set_message('Failed to update job or you do not have permission.', 'danger');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Stack+Sans+Headline:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include('reusable/nav.php'); ?>

  <div class="container mt-5">
    <h1 class="display-5 mb-4"><?= is_admin() ? 'Update Job (Admin)' : 'Update My Job' ?></h1>

    <?php get_message(); ?>

    <form action="updateJob.php" method="POST">

      <input type="hidden" name="id" value="<?php echo $job['id']; ?>">

      <h5 class="mb-3">Company Information</h5>
      
      <div class="mb-3">
        <label class="form-label">Company Name *</label>
        <input type="text" class="form-control" name="company_name" required
               value="<?php echo htmlspecialchars($job['company_name'] ?? ''); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Company Website</label>
        <input type="url" class="form-control" name="company_website"
               value="<?php echo htmlspecialchars($job['company_website'] ?? ''); ?>" 
               placeholder="https://company.com">
      </div>

      <div class="mb-3">
        <label class="form-label">Industry</label>
        <input type="text" class="form-control" name="company_industry"
               value="<?php echo htmlspecialchars($job['company_industry'] ?? ''); ?>" 
               placeholder="e.g., Technology, Finance, Healthcare">
      </div>

      <hr class="my-4">
      <h5 class="mb-3">Job Information</h5>

      <div class="mb-3">
        <label class="form-label">Job Title *</label>
        <input type="text" class="form-control" name="title"
               value="<?php echo htmlspecialchars($job['title']); ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Description *</label>
        <textarea class="form-control" name="description" required rows="3"><?php echo htmlspecialchars($job['description']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Location *</label>
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