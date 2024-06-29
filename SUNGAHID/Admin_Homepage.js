document.addEventListener('DOMContentLoaded', function() {
    var postInput = document.getElementById('postInput');
    var modal = document.getElementById('postModal');
    var span = document.getElementsByClassName('close')[0];

    postInput.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    submitPost.onclick = function() {
        var content = postContent.value;
        var audience = postAudience.value;
        var imageFile = postImage.files[0];

        if (content) {
            var formData = new FormData();
            formData.append("content", content);
            formData.append("audience", audience);
            if (imageFile) {
                formData.append("image", imageFile);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "create_post.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    loadPosts();
                    postContent.value = '';
                    postImage.value = '';
                    modal.style.display = "none";
                }
            };
            xhr.send(formData);
        }
    }

    // Function to fetch and display posts
    function loadPosts() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_posts.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                document.querySelector('.posts').innerHTML = xhr.responseText;
                attachPostEventListeners(); 
            }
        };
        xhr.send();
    }

    function attachPostEventListeners() {
        const likeBtns = document.querySelectorAll('.like-btn');
        const commentBtns = document.querySelectorAll('.comment-btn');
        const submitCommentBtns = document.querySelectorAll('.submit-comment');

        likeBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const icon = btn.querySelector('i');
                if (icon.classList.contains('fi-rs-social-network')) {
                    icon.classList.remove('fi-rs-social-network');
                    icon.classList.add('fi-ss-social-network');
                    icon.style.color = '#630E15';
                } else {
                    icon.classList.remove('fi-ss-social-network');
                    icon.classList.add('fi-rs-social-network');
                    icon.style.color = '';
                }
            });
        });

        commentBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const commentsSection = btn.closest('.post').querySelector('.comments-section');
                const icon = btn.querySelector('i');
                commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
                if (icon.classList.contains('fi-ts-comment-dots')) {
                    icon.classList.remove('fi-ts-comment-dots');
                    icon.classList.add('fi-ss-comment-dots');
                    icon.style.color = '#630E15';
                } else {
                    icon.classList.remove('fi-ss-comment-dots');
                    icon.classList.add('fi-ts-comment-dots');
                    icon.style.color = '';
                }
            });
        });

        submitCommentBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const post = btn.closest('.post');
                const commentInput = post.querySelector('.comment-input input');
                const commentsList = post.querySelector('.comments-list');

                if (commentInput.value.trim() !== '') {
                    const comment = document.createElement('div');
                    comment.className = 'comment';
                    comment.textContent = commentInput.value;
                    commentsList.appendChild(comment);
                    commentInput.value = '';
                }
            });
        });
    }

    window.onload = loadPosts;
});



// Manage account
document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("manageAccountModal");
    const manageAccountLink = document.querySelector('.manage-account');
    const closeBtn = document.querySelector('.close');

    manageAccountLink.addEventListener('click', function (e) {
        e.preventDefault();
        modal.style.display = "block";
    });

    closeBtn.addEventListener('click', function () {
        modal.style.display = "none"; 
    });

    closeBtn.onclick = function () {
        modal.style.display = "none";
        
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function goToAllAccounts() {
        closeModal(); 
        window.location.href = "allAccount.php"; 
    }

    function goToVerifyAccounts() {
        closeModal(); 
        window.location.href = "verify.php"; 
    }
});

// create post pop up
document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("postModal");
    const closeBtn = document.querySelector('#postModal .close');

    closeBtn.addEventListener('click', function () {
        modal.style.display = "none"; 
    });

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none"; 
        }
    }
});
