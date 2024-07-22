<?php
session_start();
include('../config.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['postId']) && isset($_POST['action'])) {
    $postId = $_POST['postId'];
    $userId = $_SESSION['id']; 
    $action = $_POST['action'];

 
    $response = [
        'status' => 'error',
        'message' => 'An error occurred.'
    ];

    if ($action === 'like') {

        // Check if the user has already liked the post
        $checkSql = "SELECT * FROM likes WHERE userid = '$userId' AND postid = '$postId'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {

            // User has already liked the post
            $response['status'] = 'already_liked';
            $response['message'] = 'You have already liked this post.';
        } else {

            // check if the user is not already like, add like
            $sql = "INSERT INTO likes (userid, postid) VALUES ('$userId', '$postId')";
            if ($conn->query($sql) === TRUE) {

                // Count likes for the post
                $countSql = "SELECT COUNT(id) AS like_count FROM likes WHERE postid = '$postId'";
                $countResult = $conn->query($countSql);
                $likeCount = ($countResult->num_rows > 0) ? $countResult->fetch_assoc()['like_count'] : 0;

                $response['status'] = 'success';
                $response['like_count'] = $likeCount;
            } else {
                $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else if ($action === 'unlike') {

        // check if the user is already liked
        $sql = "DELETE FROM likes WHERE userid = '$userId' AND postid = '$postId'";
        if ($conn->query($sql) === TRUE) {

            // Count ang likes sa post
            $countSql = "SELECT COUNT(id) AS like_count FROM likes WHERE postid = '$postId'";
            $countResult = $conn->query($countSql);
            $likeCount = ($countResult->num_rows > 0) ? $countResult->fetch_assoc()['like_count'] : 0;

            $response['status'] = 'success';
            $response['like_count'] = $likeCount;
        } else {
            $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    echo json_encode($response);
}
?>
