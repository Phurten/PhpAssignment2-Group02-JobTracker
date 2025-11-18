<?php
  include('functions.php');
  include('reusable/conn.php');
  secure();
  // Get schools for dropdown
  $schools = $connect->query("SELECT * FROM schools");
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $school_id = $_POST['school_id'];

    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadImage($_FILES['image']);
    }

    $query = "INSERT INTO students (name, email, phone, image, school_id) 
            VALUES ('$name', '$email', '$phone', '$imagePath', '$school_id')";
    $result = mysqli_query($connect, $query);
    if ($result) {
        echo "Student added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ontario Public Schools</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Ontario Public Schools</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add.php">Add A School</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col">
          <h1 class="display-4 mt-5 mb-5">All Student</h1>
        </div>
      </div>
    </div>
  </div>
  
  <?php 
      include('reusable/conn.php');
      $query = 'SELECT * FROM schools';
      $schools = mysqli_query($connect, $query);
  ?>

  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <form action="addStudent.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" aria-describedby="Name">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="mb-3">
              <label for="school_id" class="form-label">Select School</label>
              <select name="school_id" required class="form-control">
                  <?php while ($school = $schools->fetch_assoc()) : ?>
                      <option value="<?= $school['id'] ?>"><?= $school['School Name'] ?></option>
                  <?php endwhile; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Image</label>
              <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary" name="addSchool">Add Student</button>
          </form>
        </div>
      </div>
    </div>
  </div>


</body>
</html>