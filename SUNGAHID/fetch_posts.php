<?php
// $servername = "127.0.0.1";
// $username = "root"; 
// $password = ""; 
// $dbname = "teknospace"; 
session_start();
include('../helper.php');
include('../config.php');
$loggedUserId = $_SESSION['id'];
// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
// $conn = mysqli_connect("localhost","root","","accounts") or die("Couldn't connect");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get all posts from db together and its comments
$sql = "SELECT 
posts.id as postId,
posts.content,
posts.created_at,
posts.image_path,
poster.Id as posterId,
poster.firstName,
poster.lastName,
comments.Id as commentId,
comments.comment,
comments.created_at as dateCommented,
commenter.Id as commenterId,
commenter.firstName as commenterFname,
commenter.lastName as commenterLname 

FROM posts 
LEFT JOIN comments ON posts.id = comments.postId 
LEFT JOIN users as poster ON poster.Id = posts.userId
LEFT JOIN users as commenter ON commenter.Id = comments.userId
ORDER BY posts.created_at DESC, dateCommented DESC";

$result = $conn->query($sql);

// all post container together its comments if has
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $post_id = $row['postId'];
        
        if (!isset($posts[$post_id])) {
            // Fetch profile image from the 'profile' table
            $profile_image = ''; // Default profile image path
            
            // Fetch profile image from the 'profile' table based on userId
            $profile_query = "SELECT profile_pic FROM profile WHERE userId = '{$row['posterId']}'";
            $profile_result = $conn->query($profile_query);
            
            if ($profile_result->num_rows > 0) {
                $profile_row = $profile_result->fetch_assoc();
                $profile_image = $profile_row['profile_pic'];
            }
            
            $posts[$post_id] = [
                'id' => $row['postId'],
                'fullName' => $row['firstName'].' '.$row['lastName'],
                'datePosted' => $row['created_at'],
                'postImage' => $row['image_path'],
                'postContent' => $row['content'],
                'comments' => [],
                'profileImage' => $profile_image
            ];
        }

        // Assign comments to the corresponding post
        if (!is_null($row['commentId'])) {
            $posts[$post_id]['comments'][] = [
                'comment' => $row['comment'],
                'commenter' => $row['commenterFname'].' '.$row['commenterLname'],
            ];
        }
    }
}


if (!empty($posts)) {
   foreach($posts as $post) {
       
        echo '
            <div class="post-container">
                <div class="post">
                    <div class="post-header">
                        <img src="'.$post['profileImage'].'" alt="Profile Image">
                        <div class="post-header-info">
                            <h3>'.$post['fullName'].'</h3>
                            <p>'.relative_time($post['datePosted']).'</p>
                        </div>
                    </div>
                    <div class="post-content">
                        <p>'.$post['postContent'].'</p>';
        if (!empty($post['postImage'])) {
            echo '<img src="'.$post['postImage'].'" alt="Post Image" style="max-width: 100%; height: auto;">';
        }
        echo '
                    </div>
                    <div class="post-actions">
                        <a href="#" class="like-btn"><i class="fi fi-rs-social-network"></i> Like</a>
                        <a href="#" class="comment-btn">
                            <i class="fi fi-ts-comment-dots"></i> 
                            <small>'.count($post['comments']).'</small>
                            Comment
                        </a>
                    </div>
                    <div class="comments-section" style="display: none;">
                        <div class="comment-input">
                            <input type="text" placeholder="Write a comment...">
                            <button class="submit-comment" data-pid="'.$post['id'].'" data-uid="'.$loggedUserId .'" >
                                <i class="fi fi-ss-paper-plane-top">
                                </i>
                            </button>
                            
                        </div>
                        <div class="comments-list">';
                        if (count($post['comments']) > 0) {
                                        foreach($post['comments'] as $comment) { 
                                            echo '<div class="comment">';
                                            echo '<b>'.$comment['commenter'].'</b>';
                                            echo '<p>'.$comment['comment'].'</p>';
                                            echo '</div>';
                                        }
                                    }
                        echo '</div>
                    </div>
                </div>
            </div>';
    }
} else {
    echo '<div class="post-container">
                <div class="post">"No posts found."</div>
          </div>';
}

$conn->close();
?>
