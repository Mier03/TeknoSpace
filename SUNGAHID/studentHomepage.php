<?php
session_start();

// this session is get from the login
$loggedUserId = $_SESSION['id'];

include('../config.php');
include('../helper.php');

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
commenter.Id as commenterId,
commenter.firstName as commenterFname,
commenter.lastName as commenterLname 
FROM posts 
LEFT JOIN comments ON posts.id = comments.postId 
LEFT JOIN users as poster ON poster.Id = posts.userId
LEFT JOIN users as commenter ON commenter.Id = comments.userId
ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

// all post container together its comments if has
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $post_id = $row['postId'];
        
        if (!isset($posts[$post_id])) {
            $posts[$post_id] = [
                'id' => $row['postId'],
                'fullName' => $row['firstName'].' '.$row['lastName'],
                'datePosted'=>$row['created_at'],
                'postImage'=>$row['image_path'],
                'postContent'=>$row['content'],
                'comments' => []
            ];
        }

        // this is to assign the posts comments
        if (!is_null($row['commentId'])) {
            $posts[$post_id]['comments'][] = [
                'comment' => $row['comment'],
                'commenter' => $row['commenterFname'].' '.$row['commenterLname'],
            ];
        }
    }
}

// when submit comment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_comment_btn'])){
        if(! empty($_POST['post_comment'])) {
            $postId = $conn->real_escape_string($_POST['pid']);
            $comment = $conn->real_escape_string($_POST['post_comment']);
            
            // saving new comment in db
            $conn->query("INSERT INTO comments (postId, userId, comment) 
                    VALUES ('$postId', '$loggedUserId', '$comment')");

            $_POST = array();

            Header('Location: '.$_SERVER['PHP_SELF']);
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknospace</title>
    <link rel="stylesheet" href="Student_styles.css">
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-regular-straight/css/uicons-regular-straight.css'>
    
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
            <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span>
            </div>
            <div class="nav-links">
                <a href="#profile" class="icon"><i class="fi fi-ss-user"></i></a>                
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>                
                <a href="../aboutUs.php">Log Out</a>
            </div>
        </div>
    </header>
    <nav class="nav">
        <ul>
            <li><a href="Student_Homepage.html" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="#maintenance" class="icon"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="#lost&found" class="icon"><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
        </ul>
    </nav>
    <main class="main">
    <div class="posts">
        <?php
            if (count($posts) > 0) {
                foreach($posts as $post) {
                    ?>
                   <div class="post-container">
                    <div class="post">
                        <div class="post-header">
                        <!-- TO DO IMAGE -->
                            <img src="https://static.thenounproject.com/png/3918329-200.png" alt="Profile Image"> 
                            <div class="post-header-info">
                                <h3><?php echo $post['fullName']; ?></h3>
                                <p><?php echo relative_time($post['datePosted']); ?></p>
                            </div>
                        </div>
                        <div class="post-content">
                            <p><?php echo $post['postContent']; ?></p>
                            <?php 
                                $image = $post['postImage'];

                                if(! empty($image)) {
                                    echo '<img src="'.$image.'" width="100%"/>';
                                } 
                            ?>
                        </div>
                        <div class="post-actions">
                            <a href="#" class="like-btn"><i class="fi fi-rs-social-network"></i> Like</a>
                            <a href="#" class="comment-btn"><i class="fi fi-ts-comment-dots"></i>
                                <small><?php echo count($post['comments']); ?></small>
                                Comment
                            </a>
                        </div>
                        <div class="comments-section" style="display: none;">
                            <div class="comment-input">
                                <form method="POST" style="width:100%">
                                    <input type="text" name="post_comment" placeholder="Write a comment..." style="width:90%" value=""/>
                                    <button type="submit" name="submit_comment_btn">
                                        <i class="fi fi-ss-paper-plane-top"></i>
                                    </button>
                                    <input type="hidden" name="pid" value="<?php echo $post['id'];?>" />
                                </form>
                            </div>
                            <div class="comments-list">
                                <?php
                                    if (count($post['comments']) > 0) {
                                        foreach($post['comments'] as $comment) { 
                                            echo '<div class="comment">';
                                            echo '<b>'.$comment['commenter'].'</b>';
                                            echo '<p>'.$comment['comment'].'</p>';
                                            echo '</div>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php    }
            } else {
                echo ' <div class="post-container">
                 <div class="post">
                "No posts found."
                </div>
                </div>';
            }
        ?>
    </main>
    <script src="Student_Homepage.js"></script>
</body>
</html>
