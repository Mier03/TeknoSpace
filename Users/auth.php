<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['valid'])) {
        header("Location: ../login.php");
        exit();
    }
}

function checkUserRole($requiredRole) {
    if (!isset($_SESSION['userType']) || $_SESSION['userType'] !== $requiredRole) {
        header("Location: authno.php");
        exit();
    }
}
?>