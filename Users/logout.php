<?php
session_start();
$_SESSION = array();
session_destroy();
if (isset($_COOKIE['remember_me'])) {
    unset($_COOKIE['remember_me']);
    setcookie('remember_me', '', time() - 3600, '/');
}
http_response_code(200);
?>