<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

include('../config.php');
include('../helper.php');

$userId = $_SESSION['id'];
$userType = $_SESSION['userType'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];

$course = $_SESSION['course'];
$idNumber = $_SESSION['idNumber'];
$email = $_SESSION['valid'];

// Fetch profile data
$sql = "SELECT * FROM profile WHERE userId = '$userId'";
$result = $conn->query($sql);
$default_profile_image = 'https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=';
$default_cover_photo = 'https://cit.edu/wp-content/uploads/2024/03/PNG_2nd-Semester_Website-Banner-2024.png';


if ($result->num_rows > 0) {
    $profile_row = $result->fetch_assoc();

    if (!empty($profile_row['profile_pic']) && file_exists($profile_row['profile_pic'])) {
        $profilePic = $profile_row['profile_pic'];
    } else {
        $profilePic = $default_profile_image;
    }

    if (!empty($profile_row['cover_photo'])) {
        $coverPhoto = $profile_row['cover_photo'];
    } else {
        $coverPhoto = $default_cover_photo;
    }
} else {
    // Default images if no profile found
    $profilePic = $default_profile_image;
    $coverPhoto = $default_cover_photo;

    // Insert default images into the profile table
    $insert = $conn->query("INSERT INTO profile (userId, profile_pic, cover_photo) VALUES ('$userId', '$profile_image', '$cover_photo')");
    if (!$insert) {
        die("Error inserting new profile: " . $conn->error);
    }
}


// Fetch user posts only if user is Faculty
$posts = [];
if ($userType === 'Faculty' || $userType === 'Admin') {
    $sqlPosts = "SELECT p.*, u.firstName AS post_creator_name, u.lastName AS post_creator_lastname,
                 (SELECT COUNT(*) FROM likes WHERE postId = p.id) AS likes_count
                 FROM posts p 
                 JOIN users u ON p.userId = u.id
                 WHERE p.userId = ? 
                 ORDER BY p.created_at DESC";
    
    $stmt = $conn->prepare($sqlPosts);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $resultPosts = $stmt->get_result();

    if ($resultPosts->num_rows > 0) {
        while ($row = $resultPosts->fetch_assoc()) {
            // Fetch comments for this post
            $sqlComments = "SELECT c.*, u.firstName AS commenter_name, u.lastName AS commenter_lastname 
            FROM comments c
            JOIN users u ON c.userId = u.id
            WHERE c.postId = ?
            ORDER BY c.created_at DESC";
            $stmtComments = $conn->prepare($sqlComments);
            $stmtComments->bind_param("i", $row['id']);
            $stmtComments->execute();
            $resultComments = $stmtComments->get_result();
            $comments = [];
            while ($comment = $resultComments->fetch_assoc()) {
                $comments[] = $comment;
            }
            
            $row['comments'] = $comments;
            $row['comments_count'] = count($comments);
            
            // Check if the current user has liked this post
            $sqlUserLiked = "SELECT COUNT(*) AS user_liked FROM likes WHERE postId = ? AND userId = ?";
            $stmtUserLiked = $conn->prepare($sqlUserLiked);
            $stmtUserLiked->bind_param("ii", $row['id'], $userId);
            $stmtUserLiked->execute();
            $resultUserLiked = $stmtUserLiked->get_result()->fetch_assoc();
            $row['user_liked'] = $resultUserLiked['user_liked'] > 0;

            $posts[] = $row;
        }
    }
}

// Query to get user's comments
$commentsQuery = "SELECT * FROM comments WHERE userId = '{$userId}' ORDER BY comments.updated_comment_at DESC ";
$commentsResult = $conn->query($commentsQuery);

$comments = [];
$commentPostIds = [];

if ($commentsResult->num_rows > 0) {
    while ($commentRow = $commentsResult->fetch_assoc()) {
        $comments[] = [
            'commentId' => $commentRow['Id'], // Use correct column name
            'postId' => $commentRow['postId'],
            'comment' => $commentRow['comment'],
            'commenterId' => $commentRow['userId'], // Added commenterId for reference
            'created_at' => $commentRow['created_at'],
        ];

        $commentPostIds[] = $commentRow['postId'];
    }
}

// Ensure $commentPostIds is not empty before constructing the query
if (!empty($commentPostIds)) {
    $commentPostIds = array_unique($commentPostIds); // Remove duplicates
    $commentPostIds = implode(',', array_map('intval', $commentPostIds)); // Prepare IDs for query

    // Query to get posts associated with the comments along with like counts
    $postsQuery = "
        SELECT p.*, COUNT(l.id) AS like_count
        FROM posts p
        LEFT JOIN likes l ON p.id = l.postId
        WHERE p.id IN ({$commentPostIds})
        GROUP BY p.id
    ";
    $postsResult = $conn->query($postsQuery);

    $postComments = [];

    if ($postsResult->num_rows > 0) {
        while ($postRow = $postsResult->fetch_assoc()) {
            $post_id = $postRow['id'];

            $profile_image = 'https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=';

            // Fetch profile image from the 'profile' table
            $profileQuery = "SELECT profile_pic FROM profile WHERE userId = '{$postRow['userId']}'"; // Use correct column
            $profileResult = $conn->query($profileQuery);

            if ($profileResult->num_rows > 0) {
                $profileRow = $profileResult->fetch_assoc();
                if (!empty($profileRow['profile_pic']) && file_exists($profileRow['profile_pic'])) {
                    $profile_image = $profileRow['profile_pic'];
                }
            }

            $postComments[$post_id] = [
                'id' => $postRow['id'],
                'fullName' => $postRow['username'], // Use correct column for username
                'datePosted' => $postRow['created_at'],
                'postImage' => $postRow['image_path'],
                'postContent' => $postRow['content'],
                'isImportant' => $postRow['is_important'],
                'profileImage' => $profile_image,
                'likeCount' => $postRow['like_count'], // Add like count
                'comments' => []
            ];
        }

        foreach ($comments as $comment) {
            $postId = $comment['postId'];
            if (isset($postComments[$postId])) {
                $postComments[$postId]['comments'][] = [
                    'commentId' => $comment['commentId'],
                    'comment' => $comment['comment'],
                    'commenterId' => $comment['commenterId'], // Added commenterId for reference
                    'created_at' => $comment['created_at'],
                ];
            }
        }
    } else {
        // Handle the case when there are no posts found
        echo "No posts found for the given comment IDs.";
    }
} else {
    // Handle the case when there are no comments for the user
    echo "No comments found for this user.";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="Profile_styles.css">
    <link rel="icon" href="../images/urlicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.4.2/uicons-regular-straight/css/uicons-regular-straight.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="background-container">
        <img src="../images/signin-bg.png" alt="Background Image">
    </div>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <?php
                if ($userType === 'Student') {
                    echo '<a href="../users/studentHomepage.php">';
                } elseif ($userType === 'Faculty') {
                    echo '<a href="../users/facultyHomepage.php">';
                } elseif ($userType === 'Admin') {
                    echo '<a href="../users/admin.php">';
                }
                ?>
                <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span></a>
            </div>
            <div class="nav-links">
                <a href="Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>
                <a href="#" onclick="showLogoutModal(); return false;">Log Out</a>
            </div>
            <div id="logoutModal" class="logout-modal">
                <div class="logout-modal-content">
                    <img src="../images/check_gif.webp" alt="Success" class="logout-icon">
                    <p>Logged out successfully</p>
                </div>
            </div>
            <div class="burger-icon">
                <i class='bx bx-menu burger-icon'></i>
            </div>
        </div>
    </header>
    <!-- navmodal -->
    <div id="navModal" class="navmodal">
        <div class="navmodal-content">
            <span class="close">&times;</span>
            <a href="../profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i><span class="nav-link"> Profile</span></a>
            <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i><span class="nav-link"> Notifications</span></a>
            <a href="#" onclick="showLogoutModal(); return false;"><i class='bx bx-exit'></i> Log Out</a>
        </div>
    </div>
    <main>
        <section id="profile">
            <div class="cover-photo">
                <img src="<?php echo $coverPhoto; ?>" alt="Cover Photo">
                <button class="change-photo" id="change-cover"><i class='bx bxs-camera'></i></i></button>
            </div>
            <div class="profile-info">
                <div class="profile-photo">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                    <button class="change-photo" id="change-profile"><i class='bx bxs-camera'></i></i></button>
                </div>
                <div class="user-details">
                    <h1><?php echo $firstName.' '.$lastName; ?></h1>
                    <p class="user-role"><?php echo $userType; ?></p>
                </div>
            </div>
            <div class="additional-details">
                <h2>Details</h2>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>ID Number:</strong> <?php echo $idNumber; ?></p>
            </div>
        </section>

        <?php if ($userType === 'Student') : ?>
            <section id="posts">
                <h2>Comments</h2>
                <div id="post-list">
                    <?php if (!empty($postComments)) : ?>
                        <?php foreach ($postComments as $post) : ?>
                            <div class="post" data-post-id="<?php echo htmlspecialchars($post['id']); ?>">
                                <div class="post-header">
                                    <div class="profile-pic">
                                        <img src="<?php echo htmlspecialchars($post['profileImage']); ?>" alt="Profile Photo">
                                    </div>
                                    <div class="post-header-info">
                                        <h3><?php echo htmlspecialchars($post['fullName']); ?></h3>
                                        <span class="post-date"><?php echo htmlspecialchars(relative_time($post['datePosted'])); ?></span>
                                    </div>
                                    <!-- <div class="post-options">
                            <button class="post-options-btn"><i class='bx bx-dots-horizontal-rounded'></i></button>
                            <div class="post-options-content">
                                <a href="#" class="edit-post">Edit Post</a>
                                <a href="#" class="delete-post">Delete Post</a>
                            </div>
                        </div> -->
                                </div>
                                <div class="post-content">
                                    <p><?php echo htmlspecialchars($post['postContent']); ?></p>
                                    <?php if (!empty($post['postImage'])) : ?>
                                        <img src="<?php echo htmlspecialchars($post['postImage']); ?>" alt="Post Image" class="post-image" data-full-image="<?php echo htmlspecialchars($post['postImage']) ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="post-actions">
                                    <a href="#" class="like-btn">
                                        <i class="fi fi-rs-social-network"></i>
                                        <small><?php echo ($post['likeCount'])?></small> 
                                        Likes
                                    </a>
                                    <a href="#" class="comment-btn">
                                        <i class="fi fi-ts-comment-dots"></i> 
                                        <small><?php echo count($post['comments'])?></small>
                                        Comment
                                    </a>
                                </div>
                                <div class="comments-section" style="display:none">
                                    <?php
                                    foreach ($post['comments'] as $comment) {
                                        echo '<div class="comment">';
                                        echo '<div class="comment-header">';
                                        // Display the commenter's name
                                        $commenterName = htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']);
                                        echo '<span class="comment-username bold-text">' . $commenterName . '</span>';
                                        // echo '<span class="comment-date">' . htmlspecialchars(relative_time($comment['created_at'])) . '</span>';

                                        // Options button and dropdown
                                        echo '<button class="comment-options-btn"><i class="bx bx-dots-horizontal-rounded"></i></button>';
                                        echo '<div class="comment-options-content" style="display: none;">';
                                        echo '<a href="#" class="edit-comment" data-comment-id="' . $comment['commentId'] . '" data-comment-text="' . htmlspecialchars($comment['comment']) . '"><i class="bx bx-edit"></i> 
                                        <span>Edit Comment</span></a>';
                                        echo '<a href="#" class="delete-comment" data-comment-id="' . $comment['commentId'] . '"><i class="bx bx-trash"></i>   <span>Delete Comment</span></a>';
                                        echo '</div>';

                                        echo '</div>'; // Close comment-header

                                        // Display the comment content
                                        echo '<p class="comment-content">' . htmlspecialchars($comment['comment']) . '</p>';

                                        // Edit comment form
                                        echo '<div class="edit-comment-form" data-comment-id="' . $comment['commentId'] . '" style="display: none;">';
                                        echo '<textarea class="edit-comment-text">' . htmlspecialchars($comment['comment']) . '</textarea>';
                                        echo '<button data-comment-id="' . $comment['commentId'] . '" data-comment-text="' . htmlspecialchars($comment['comment']) . '" class="save-edit-comment">Save</button>';
                                        echo '<button class="cancel-edit-comment">Cancel</button>';
                                        echo '</div>';

                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p style="margin-left: 1rem;">No comments available.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>


        <?php if ($userType === 'Faculty' ||$userType === 'Admin'  ) : ?>
            <section id="posts">
                <h2>Posts</h2>
                <div id="post-list">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <?php
                            $postClass = $post['is_important'] ? 'important-post' : '';
                            ?>
                            <div class="post <?php echo $postClass; ?>" data-post-id="<?php echo htmlspecialchars($post['id']); ?>">
                                <div class="post-header">
                                    <div class="profile-pic">
                                        <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                                    </div>

                                    <div class="post-header-info">
                                    <h3><?php echo htmlspecialchars($post['post_creator_name']) . ' ' . htmlspecialchars($post['post_creator_lastname']); ?></h3>
                                        <span class="post-date"><?php echo htmlspecialchars(relative_time($post['created_at'])); ?></span>
                                    </div>

                                    <?php if ($post['is_important']) : ?>
                                        <?php echo '<span class="important-badge"><strong>! IMPORTANT</strong></span>'; ?>
                                    <?php endif; ?>


                                    <div class="post-options">
                                        <button class="post-options-btn"><i class='bx bx-dots-horizontal-rounded'></i></button>
                                        <div class="post-options-content">
                                            <a href="#" class="edit-post">Edit Post</a>
                                            <a href="#" class="delete-post">Delete Post</a>

                                            <?php if ($post['posttype'] === 'Announcement' || $post['posttype'] === 'Maintenance') : ?>
                                                <?php if ($post['is_important']) : ?>
                                                    <a href="#" class="toggle-important" data-action="remove">Remove Important</a>
                                                <?php else : ?>
                                                    <a href="#" class="toggle-important" data-action="make">Make Important</a>
                                                <?php endif; ?>
                                            <?php elseif ($post['posttype'] === 'Lost & Found' && $post['status'] !== 'found') : ?>
                                                <a href="#" class="toggle-found" data-post-id="<?php echo $post['id']; ?>">Found</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-content">
                                <?php if ($post['posttype'] === 'Lost & Found') : ?>
                                    <div>
                                        <?php if ($post['status'] === 'found') : ?>
                                            <h1 class="found-status">! FOUND</h1>
                                        <?php else : ?>
                                            <h1 class="lost-status">! LOST</h1>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                                    <?php if (!empty($post['image_path'])) : ?>
                                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" class="post-image" data-full-image="<?php echo htmlspecialchars($post['image_path']) ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="post-actions">
                                            <a href="#" class="like-btn" data-postid="'.$post['id'].'">
                                        <i class=" fi-rs-social-network"></i>
                                        <small class="likes-count"><?php echo $post['likes_count']; ?></small> 
                                        Likes
                                    </a>

                                    <a href="#" class="comment-btn">
                                        <i class="fi-ts-comment-dots"></i>
                                        <small><?php echo $post['comments_count']; ?></small> Comment
                                    </a>
                            </div>
                        <div class="comments-section" id="comments-<?php echo $post['id']; ?>" style="display: none;">
                            <div class="comments-list">
                                <?php if (count($post['comments']) > 0) : ?>
                                    <?php foreach ($post['comments'] as $comment) : ?>
                                        <div class="comment" >
                                        <span class="commenter"><?php echo htmlspecialchars($comment['commenter_name']); ?></span>
                                            <p class="comment-content"><?php echo htmlspecialchars($comment['comment']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="comment">
                                    <p>No comments yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                            </div>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <p style="margin-left: 1rem;">No posts available.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

       
    </main>
<div id="editPostModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Post</h2>
        <textarea id="editPostContent"></textarea>
        <div class="button-container">
            <button id="saveEditPost">Save</button>
            <button id="cancelEditPost">Cancel</button>
        </div>
    </div>
</div>

<div id="deletePostModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Delete Post</h2>
        <p>Are you sure you want to delete this post?</p>
        <div class="button-container">
            <button id="confirmDeletePost">Delete</button>
            <button id="cancelDeletePost">Cancel</button>
        </div>
    </div>
</div>    
<div id="foundItemModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Item Found</h2>
        <p>Is this item found?</p>
        <div class="button-container">
            <button id="confirmFoundItem">Found</button>
            <button id="cancelFoundItem">Cancel</button>
        </div>
    </div>
</div>

    <form id="uploadForm" method="post" action="ProfileCoverUpload.php" enctype="multipart/form-data">
        <input type="file" id="fileUpload" name="fileUpload" style="display: none;">
        <input type="hidden" id="uploadType" name="uploadType">
    </form>
    <!-- Interactive Image -->
    <div id="imageModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="fullImage">
    </div>
    <script src="Profile_Page.js">
    </script>





</body>

</html>