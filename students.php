<?php 
  include('functions.php');
  secure();
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
  <?php include('reusable/nav.php'); ?>

  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col">
          <h1 class="display-4 mt-5 mb-5">All Students</h1>
        </div>
      </div>
    </div>
  </div>

  <?php 
      require('reusable/conn.php');
      $query = "SELECT students.*, schools.`School Name` FROM students 
                    LEFT JOIN schools ON students.school_id = schools.id";
      $students = mysqli_query($connect, $query);
      // echo '<pre>';
      // echo print_r($students);
      // echo '</pre>';
  ?>

  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col">
          <?php get_message(); ?>
        </div>
      </div>
      <div class="row">
        <?php
          foreach($students as $student){
            echo '<div class="col-md-4 mt-2 mb-2">
                    <div class="card">
                      <div class="card-body">
                        <img src="' . $student['image'] . '" width="50">
                        <h5 class="card-title">' . $student['name'] . '</h5>
                        <p class="card-text">' . $student['School Name'] . '</p>
                      </div>
                    </div>
                  </div>';  
          }
        ?>
      </div>
    </div>
  </div>


</body>
</html>