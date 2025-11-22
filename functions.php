<?php
//checking if session is not started, then start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

function is_admin() {
    return isset($_SESSION['email']) && $_SESSION['email'] === 'admin@example.com';
}

function can_view_all_jobs() {
    return is_admin();
}

function can_edit_all_jobs() {
    return is_admin();
}
?>
