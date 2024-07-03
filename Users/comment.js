document.addEventListener('DOMContentLoaded', function() {
    //fetch and display posts
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
        //like
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
                    xhr.open("POST", "create_comment.php", true);
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
});