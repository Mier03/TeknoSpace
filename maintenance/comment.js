document.addEventListener('DOMContentLoaded', function() {
    //fetch and display posts
    function loadPosts() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "maintenance_post.php", true);
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
        //like
        likeBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
    
                const postId = this.getAttribute('data-postid');
                const icon = this.querySelector('i');
                const likesCountElement = this.querySelector('.likes-count');
                const action = icon.classList.contains('fi-ss-social-network') ? 'unlike' : 'like';
    
                // Send AJAX request to like.php
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../users/like.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                likesCountElement.textContent = response.like_count;
    
                                // Toggle like/unlike icon based on response
                                if (action === 'like') {
                                    icon.classList.add('fi-ss-social-network');
                                    icon.classList.remove('fi-rs-social-network');
                                    icon.style.color = '#630E15';
                                } else {
                                    icon.classList.remove('fi-ss-social-network');
                                    icon.classList.add('fi-rs-social-network');
                                    icon.style.color = '';
                                }
                            } else if (response.status === 'already_liked') {
                                console.log(response.message);
                            } else {
                                console.error('Error:', response.message);
                            }
                        } else {
                            console.error('Error:', xhr.status);
                        }
                    }
                };
                xhr.send('postId=' + postId + '&action=' + action);
            });
        });
        //comment
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
        //postcomment
        submitCommentBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const post = btn.closest('.post');
                const commentInput = post.querySelector('.comment-input input');
                const commentsList = post.querySelector('.comments-list');

                if (commentInput.value.trim() !== '') {
                    var formData = new FormData();
                    formData.append("postId", btn.getAttribute('data-pid'));
                    formData.append("postComment", commentInput.value.trim());
                    formData.append("userId", btn.getAttribute('data-uid'));

                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "../users/create_comment.php", true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            loadPosts();
                           // console.log('test');TO DO  need i fix
                            post.querySelector('.comments-section').style.display='block';

                        }
                    };
                    xhr.send(formData);
                    
                    
                }
            });
        });
    }
    //reload page
    window.onload = loadPosts;
})
