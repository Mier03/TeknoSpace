 /**LOGOUT MODAL */
 function showLogoutModal() {
    console.log("Logout function called");
    var modal = document.getElementById('logoutModal');
    var navModal = document.getElementById('navModal');
    if (modal) {
        modal.style.display = "block";
        // Close Burger Icon
        if (navModal) {
            navModal.classList.remove("active");
        }
        setTimeout(function() {
            modal.style.display = "none";
            window.location.href = "../aboutUs.php";
        }, 1250);
    } else {
        console.error("Logout modal not found");
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var logoutLink = document.querySelector('#navModal a[onclick*="showLogoutModal"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLogoutModal();
        });
    }

    var burgerIcon = document.querySelector(".burger-icon");
    var navLinks = document.querySelector(".nav-links");
    var modal = document.getElementById('navModal');
    var overlay = document.querySelector(".overlay");
    var closeBtn = document.querySelector(".close");

    burgerIcon.addEventListener("click", function() {
        modal.classList.toggle("active");
        overlay.classList.toggle("active");
    });

    function closeModal() {
        modal.classList.remove("active");
        overlay.classList.remove("active");
    }

    closeBtn.addEventListener("click", closeModal);
    overlay.addEventListener("click", closeModal);
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('post-list').addEventListener('click', function(event) {
        var button = event.target.closest('.post-options-btn');
        if (button) {
            button.nextElementSibling.classList.toggle('show');
        }



        if (event.target.classList.contains('edit-post')) {
            event.preventDefault();
            var post = event.target.closest('.post');
            var postId = post.dataset.postId;
            var contentElement = post.querySelector('.post-content p');
            var originalContent = contentElement.textContent;

            contentElement.innerHTML = `
            <textarea>${originalContent}</textarea>
            <button class="save-edit">Save</button>
            <button class="cancel-edit">Cancel</button>
        `;

            post.querySelector('.save-edit').addEventListener('click', function() {
                var newContent = post.querySelector('textarea').value;
                saveEdit(postId, newContent, contentElement, originalContent);
            });

            post.querySelector('.cancel-edit').addEventListener('click', function() {
                contentElement.innerHTML = originalContent;
            });


        }

        if (event.target.classList.contains('delete-post')) {
            event.preventDefault();
            var post = event.target.closest('.post');
            var postId = post.dataset.postId;
            if (confirm('Are you sure you want to delete this post?')) {
                deletePost(postId, post);
            }


        }

        if (event.target.classList.contains('toggle-important')) {
            event.preventDefault();
            const postId = event.target.closest('.post').dataset.postId;
            const action = event.target.dataset.action;
            toggleImportance(postId, action, event.target);

        }

        if (event.target.classList.contains('toggle-found')) {
            event.preventDefault();
            const postId = event.target.closest('.post').dataset.postId;
            const action = event.target.dataset.action;
            toggleFound(postId, action, event.target);

        }
    });

    function toggleImportance(postId, action, toggleLink) {
        fetch('update_importance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `postId=${postId}&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const post = toggleLink.closest('.post');
                    const importantBadge = post.querySelector('.important-badge');

                    if (action === 'make') {
                        if (!importantBadge) {
                            const newBadge = document.createElement('span');
                            newBadge.classList.add('important-badge');
                            newBadge.innerHTML = '<strong>! IMPORTANT</strong>';
                            post.querySelector('.post-header-info').after(newBadge);
                        }
                        toggleLink.innerText = 'Remove Important';
                        toggleLink.setAttribute('data-action', 'remove');
                        post.classList.add('important-post');
                    } else {
                        if (importantBadge) {
                            importantBadge.remove();
                        }
                        toggleLink.innerText = 'Make Important';
                        toggleLink.setAttribute('data-action', 'make');
                        post.classList.remove('important-post');
                    }
                } else {
                    alert('Failed to update importance');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function toggleFound(postId, action, toggleLink) {
        if (confirm('Is this item found?')) {
            fetch('update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `postId=${postId}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const post = toggleLink.closest('.post');
                        const lostHeading = post.querySelector('h1');

                        if (lostHeading) {
                            lostHeading.innerText = '! FOUND';
                            lostHeading.style.color = '#800000'; // Optionally change the color if needed
                        }
                    } else {
                        alert('Failed to update status: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                });
        }
    }


    function saveEdit(postId, newContent, contentElement, originalContent) {
        fetch('edit_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `postId=${postId}&content=${encodeURIComponent(newContent)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentElement.innerHTML = newContent;
                } else {
                    alert('Failed to save edit');
                    contentElement.innerHTML = originalContent;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                contentElement.innerHTML = originalContent;
            });
    }

    function deletePost(postId, postElement) {
        fetch('delete_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `postId=${postId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    postElement.remove();
                } else {
                    alert('Failed to delete post');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    document.getElementById('change-profile').addEventListener('click', function() {
        document.getElementById('uploadType').value = 'profile';
        document.getElementById('fileUpload').click();
    });

    document.getElementById('change-cover').addEventListener('click', function() {
        document.getElementById('uploadType').value = 'cover';
        document.getElementById('fileUpload').click();
    });

    document.getElementById('fileUpload').addEventListener('change', function() {
        document.getElementById('uploadForm').submit();
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.post-options')) {
            document.querySelectorAll('.post-options-content').forEach(function(options) {
                options.classList.remove('show');
            });
        }
    });
    //Image Modal
    var imageModal = document.getElementById('imageModal');
    var modalImg = document.getElementById("fullImage");
    var span = document.getElementsByClassName("close")[0];

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('post-image')) {
            imageModal.style.display = "block";
            modalImg.src = e.target.getAttribute('data-full-image');
        }
    });

    span.onclick = function() {
        imageModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == imageModal) {
            imageModal.style.display = "none";
        }
    }
});


  // Edit and delete comment toggle
document.addEventListener('DOMContentLoaded', function() {
    const optionsButtons = document.querySelectorAll('.comment-options-btn');

    optionsButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const optionsContent = this.nextElementSibling;

            // Close all other open dropdowns
            document.querySelectorAll('.comment-options-content').forEach(dropdown => {
                if (dropdown !== optionsContent) {
                    dropdown.style.display = 'none';
                }
            });

            optionsContent.style.display = optionsContent.style.display === 'none' ? 'block' : 'none';
        });
    });

    // Edit comment
    document.querySelectorAll('.edit-comment').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const commentText = this.getAttribute('data-comment-text');
            edit_comment(commentId, commentText);
        });
    });

    // Delete comment
    document.querySelectorAll('.delete-comment').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            delete_comment(commentId);
        });
    });

    // Attach event listeners to posts
    attachPostEventListeners();
});

function attachPostEventListeners() {
    const commentBtns = document.querySelectorAll('.comment-btn');
    const likeBtns = document.querySelectorAll('.like-btn');
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
            xhr.open('POST', 'like.php', true);
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
    // Comment functionality
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
    
}


function edit_comment(commentId, currentText) {
    const newText = prompt('Edit your comment:', currentText);

    if (newText !== null && newText !== currentText) {

        fetch('update_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}&new_text=${encodeURIComponent(newText)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    document.querySelector(`#comment-${commentId} .comment-text`).textContent = newText;
                    
                    dropdown.style.display = 'none';
                    console.log('Comment updated successfully');
                    location.reload();

                } else {
                    console.error('Failed to update comment:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

function delete_comment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {

        fetch('delete_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    dropdown.style.display = 'none';
                    document.querySelector(`#comment-${commentId}`).remove();
                    console.log('Comment deleted successfully');
                    location.reload();

                } else {
                    console.error('Failed to delete comment:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}
