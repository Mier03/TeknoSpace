document.addEventListener('DOMContentLoaded', function() {
    const likeBtns = document.querySelectorAll('.like-btn');
    const commentBtns = document.querySelectorAll('.comment-btn');
    const submitCommentBtns = document.querySelectorAll('.submit-comment');
    //const submitCommentBtns = document.querySelectorAll('.submit-comment');

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
                    const comment = document.createElement('div');
                    comment.className = 'comment';
                    comment.textContent = commentInput.value;
                    commentsList.appendChild(comment);
                    commentInput.value = '';
                }
            });
        });
    }
    //reload page
    window.onload = loadPosts;
});