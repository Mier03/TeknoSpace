<?php
session_start();
include('../config.php');

// when submit comment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $postId = $conn->real_escape_string($_POST['postId']);
            $postComment = $conn->real_escape_string($_POST['postComment']);
            $userId = $conn->real_escape_string($_POST['userId']);

            // saving new comment in db
            $conn->query("INSERT INTO comments (postId, userId, comment) 
                    VALUES ('$postId', '$userId', '$postComment')");

            $_POST = array();

            Header('Location: '.$_SERVER['PHP_SELF']);
        
    
}
?>
