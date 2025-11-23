<?php
include('functions.php');
secure();
include('reusable/conn.php');

if (is_admin()) {
    $users = mysqli_query($conn, "SELECT * FROM users");
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (is_admin() && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id']; // Admin can assign to any user
    } else {
        $user_id = $_SESSION['id']; // Regular users can only add for themselves
    }
    
    // Company information
    $company_name = trim($_POST['company_name']);
    $company_website = trim($_POST['company_website']);
    $company_industry = trim($_POST['company_industry']);
    
    // Job information
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $status = $_POST['status'];
    $date_applied = $_POST['date_applied'];

    if (empty($company_name) || empty($title) || empty($description) || empty($location) || empty($status) || empty($date_applied)) {
        $error_message = 'Please fill all required fields!';
    } else {
        
        // First, check if company already exists
        $check_company = "SELECT id FROM companies WHERE name = '" . mysqli_real_escape_string($conn, $company_name) . "'";
        $company_result = mysqli_query($conn, $check_company);
        
        if (mysqli_num_rows($company_result) > 0) {
            // Company exists, use existing ID
            $company_row = mysqli_fetch_assoc($company_result);
            $company_id = $company_row['id'];
        } else {
            // Company doesn't exist, create new one
            $insert_company = "INSERT INTO companies (name, website, industry) VALUES (
                '" . mysqli_real_escape_string($conn, $company_name) . "', 
                '" . mysqli_real_escape_string($conn, $company_website) . "', 
                '" . mysqli_real_escape_string($conn, $company_industry) . "'
            )";
            
            if (mysqli_query($conn, $insert_company)) {
                $company_id = mysqli_insert_id($conn);
            } else {
                $error_message = "Error creating company: " . mysqli_error($conn);
            }
        }
        
        // If we have a company_id, create the job
        if (!$error_message && isset($company_id)) {
            $job_query = "INSERT INTO jobs (user_id, company_id, title, description, location, status, date_applied)
                          VALUES ('$user_id', '$company_id', '" . mysqli_real_escape_string($conn, $title) . "', 
                          '" . mysqli_real_escape_string($conn, $description) . "', '" . mysqli_real_escape_string($conn, $location) . "', 
                          '$status', '$date_applied')";
            
            if (mysqli_query($conn, $job_query)) {
                set_message('Job and company added successfully!', 'success');
                header('Location: jobs.php');
                exit;
            } else {
                $error_message = "Error creating job: " . mysqli_error($conn);
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
        <h1 class="display-4 mt-5 mb-5">Add New Job Application</h1>
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

          <?php if (is_admin()): ?>
          <div class="mb-3">
            <label for="user_id" class="form-label">Select User (Admin Only)</label>
            <select name="user_id" required class="form-control">
              <option value="">Choose a user...</option>
              <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <?php endif; ?>

          <h5 class="mb-3">Company Information</h5>
          
          <div class="mb-3">
            <label for="company_name" class="form-label">Company Name *</label>
            <input type="text" class="form-control" name="company_name" required 
                   placeholder="Enter company name">
          </div>

          <div class="mb-3">
            <label for="company_website" class="form-label">Company Website</label>
            <input type="url" class="form-control" name="company_website" 
                   placeholder="https://company.com (optional)">
          </div>

          <div class="mb-3">
            <label for="company_industry" class="form-label">Industry</label>
            <input type="text" class="form-control" name="company_industry" 
                   placeholder="e.g., Technology, Finance, Healthcare (optional)">
          </div>

          <hr class="my-4">
          <h5 class="mb-3">Job Information</h5>

          <div class="mb-3">
            <label class="form-label">Job Title *</label>
            <input type="text" class="form-control" name="title" required 
                   placeholder="e.g., Software Developer Intern">
          </div>

          <div class="mb-3">
            <label class="form-label">Description *</label>
            <textarea class="form-control" name="description" required rows="3"
                      placeholder="Job description, requirements, etc."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Location *</label>
            <input type="text" class="form-control" name="location" required 
                   placeholder="e.g., Toronto, ON or Remote">
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
