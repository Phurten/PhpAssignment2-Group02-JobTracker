<?php
session_start();

function secure() {
    if(!isset($_SESSION['id'])) {
        header('Location: login.php');
        exit;
    }
}

function set_message($msg, $class) {
    $_SESSION['message'] = $msg;
    $_SESSION['className'] = $class;
}

function get_message() {
    if(isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['className'] . '">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['className']);
    }
}
?>
