<?php
session_start();
include('../helper.php');
include('../config.php');
$loggedUserId = $_SESSION['id'];

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch posts and sort by is_important descending, then by created_at descending
$sql = "SELECT 
           posts.id AS postId,
           posts.content,
           posts.created_at,
           posts.image_path,
           posts.is_important,
           poster.id AS posterId,
           poster.firstName,
           poster.lastName,
           comments.id AS commentId,
           comments.comment,
           comments.created_at AS dateCommented,
           commenter.id AS commenterId,
           commenter.firstName AS commenterFname,
           commenter.lastName AS commenterLname,
           (SELECT COUNT(*) FROM likes WHERE postid = posts.id) AS like_count,
           (SELECT COUNT(*) FROM likes WHERE postid = posts.id AND userid = '$loggedUserId') AS user_liked
       FROM posts 
       LEFT JOIN comments ON posts.id = comments.postId 
       LEFT JOIN users AS poster ON poster.id = posts.userId
       LEFT JOIN users AS commenter ON commenter.id = comments.userId
       WHERE posts.posttype = 'Maintenance'
       GROUP BY posts.id, comments.id, poster.id, commenter.id
       ORDER BY posts.is_important DESC, posts.created_at DESC, dateCommented DESC";

$result = $conn->query($sql);

// All post container together with its comments if has
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $post_id = $row['postId'];
        
        if (!isset($posts[$post_id])) {
            // Fetch profile image from the 'profile' table
            $profile_image = 'https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I='; 
            
            // Fetch profile image from the 'profile' table based on userId
            $profile_query = "SELECT profile_pic FROM profile WHERE userId = '{$row['posterId']}'";
            $profile_result = $conn->query($profile_query);
            
            if ($profile_result->num_rows > 0) {
                $profile_row = $profile_result->fetch_assoc();
                if (!empty($profile_row['profile_pic']) && file_exists($profile_row['profile_pic'])) {
                    $profile_image = $profile_row['profile_pic'];
                }
            }
            
            $posts[$post_id] = [
                'id' => $row['postId'],
                'fullName' => $row['firstName'].' '.$row['lastName'],
                'datePosted' => $row['created_at'],
                'postImage' => $row['image_path'],
                'postContent' => $row['content'],
                'isImportant' => $row['is_important'],
                'comments' => [],
                'likes' => $row['like_count'],
                'user_liked' => $row['user_liked'],
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
    foreach ($posts as $post) {
        $postClass = $post['isImportant'] ? 'important-post' : '';
        
        echo '
        <div class="post-container '.$postClass.'">
            <div class="post">
                <div class="post-header">';
        if ($post['isImportant']) {
            echo '<span class="important-badge"><strong>IMPORTANT</strong></span>';
        }
        echo '
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
                    <a href="#" class="like-btn" data-postid="'.$post['id'].'">
                        <i class="fi '.($post['user_liked'] ? 'fi-ss-social-network' : 'fi-rs-social-network').'"></i>
                        <small class="likes-count">'.$post['likes'].'</small> 
                        Likes
                    </a>
                    <a href="#" class="comment-btn">
                        <i class="fi fi-ts-comment-dots"></i> 
                        <small>'.count($post['comments']).'</small>
                        Comment
                    </a>
                </div>
                <div class="comments-section" style="display: none;">
                    <div class="comment-input">
                        <input type="text" placeholder="Write a comment...">
                        <button class="submit-comment" data-pid="'.$post['id'].'" data-uid="'.$loggedUserId.'">
                            <i class="fi fi-ss-paper-plane-top"></i>
                        </button>
                    </div>
                    <div class="comments-list">';
        if (count($post['comments']) > 0) {
            foreach ($post['comments'] as $comment) {
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
