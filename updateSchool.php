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
      <a class="navbar-brand" href="#">Ontario Public Schools</a>
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
          <h1 class="display-4 mt-5 mb-5">Update</h1>
        </div>
      </div>
    </div>
  </div>
  
  <?php 
      include('reusable/conn.php');
      include('functions.php');
      if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM schools WHERE id=$id";
        $result = mysqli_query($connect, $query);
        $school = $result->fetch_assoc();
      }

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $schoolName = $_POST['schoolName'];
        $schoolLevel = $_POST['schoolLevel'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
    
        $query = "UPDATE schools SET `school Name`='$schoolName', `school Level`='$schoolLevel', `Phone`='$phone', `Email`='$email' WHERE id=$id";
        $result = mysqli_query($connect, $query);

        if($result){
          set_message('School was updated successfully!', 'success');
          header("Location: index.php"); 
        }else{
          echo "Failed: " . mysqli_error($connect);
        }
    }


  ?>

  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <form action="updateSchool.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $school['id'] ?>">
            <div class="mb-3">
              <label for="schoolName" class="form-label">School Name</label>
              <input type="text" class="form-control" id="schoolName" name="schoolName" aria-describedby="school Name" value="<?php echo $school['School Name'] ?>">
            </div>
            <div class="mb-3">
              <label for="schoolLevel" class="form-label">School Level</label>
              <input type="text" class="form-control" id="schoolLevel" name="schoolLevel"  value="<?php echo $school['School Level'] ?>">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone"  value="<?php echo $school['Phone'] ?>">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email"  value="<?php echo $school['Email'] ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="addSchool">Update School</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>