<?php
require('reusable/conn.php');
include('functions.php');

if (isset($_GET['id'])) {  
    $id = $_GET['id'];

    $query = "DELETE FROM jobs WHERE id = '$id'";
    $job = mysqli_query($conn, $query);

    if ($job) {
        set_message('Job was deleted successfully!', 'danger');
        header('Location: jobs.php'); 
        exit; 
    } else {
        echo "Failed: " . mysqli_error($conn);
    }

} else {
    echo "Not Authorized";
}
?>
