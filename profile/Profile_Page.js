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

        /* POST MODAL */
        let currentPostId;
        let currentPost;
        
        if (event.target.classList.contains('edit-post')) {
            event.preventDefault();
            currentPost = event.target.closest('.post');
            currentPostId = currentPost.dataset.postId;
            let contentElement = currentPost.querySelector('.post-content p');
            let originalContent = contentElement.textContent;
            
            let modal = document.getElementById('editPostModal');
            let textarea = document.getElementById('editPostContent');
            textarea.value = originalContent;
            modal.style.display = "block";
        }
        
        if (event.target.classList.contains('delete-post')) {
            event.preventDefault();
            currentPost = event.target.closest('.post');
            currentPostId = currentPost.dataset.postId;
            
            let modal = document.getElementById('deletePostModal');
            modal.style.display = "block";
        }
        
        document.querySelectorAll('.modal .close').forEach(closeBtn => {
            closeBtn.onclick = function() {
                this.closest('.modal').style.display = "none";
            }
        });
        
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
        
        document.getElementById('saveEditPost').addEventListener('click', function() {
            let newContent = document.getElementById('editPostContent').value;
            let contentElement = currentPost.querySelector('.post-content p');
            saveEdit(currentPostId, newContent, contentElement, contentElement.textContent);
            document.getElementById('editPostModal').style.display = "none";
        });
        
        document.getElementById('cancelEditPost').addEventListener('click', function() {
            document.getElementById('editPostModal').style.display = "none";
        });
        
        document.getElementById('confirmDeletePost').addEventListener('click', function() {
            deletePost(currentPostId, currentPost);
            document.getElementById('deletePostModal').style.display = "none";
        });
        
        document.getElementById('cancelDeletePost').addEventListener('click', function() {
            document.getElementById('deletePostModal').style.display = "none";
        });



        /*if (event.target.classList.contains('edit-post')) {
            event.preventDefault();
            var post = event.target.closest('.post');
            var postId = post.dataset.postId;
            var contentElement = post.querySelector('.post-content p');
            var originalContent = contentElement.textContent;

            contentElement.innerHTML = `
             <textarea>${originalContent}</textarea>
        <div class="button-container">
            <button class="save-edit">Save</button>
            <button class="cancel-edit">Cancel</button>
        </div>
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


        }*/


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

    let currentPostId, currentAction, currentToggleLink;

    // Function to open the modal
    function openFoundModal(postId, action, toggleLink) {
        currentPostId = postId;
        currentAction = action;
        currentToggleLink = toggleLink;
        document.getElementById('foundItemModal').style.display = 'block';
    }
    
    // Event listener for the "Found" button in the post options
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('toggle-found')) {
            event.preventDefault();
            const postId = event.target.closest('.post').dataset.postId;
            const action = 'found';
            openFoundModal(postId, action, event.target);
        }
    });
    
    // Confirm button in the modal
    document.getElementById('confirmFoundItem').addEventListener('click', function() {
        toggleFound(currentPostId, currentAction, currentToggleLink);
        document.getElementById('foundItemModal').style.display = 'none';
    });
    
    // Cancel button in the modal
    document.getElementById('cancelFoundItem').addEventListener('click', function() {
        document.getElementById('foundItemModal').style.display = 'none';
    });
    
    // Function to update the item status
    function toggleFound(postId, action, toggleLink) {
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
                }
            } 
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        });
    }
    
    // Close the modal if user clicks outside of it
    window.onclick = function(event) {
        if (event.target == document.getElementById('foundItemModal')) {
            document.getElementById('foundItemModal').style.display = "none";
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

            // get parent .comment
            const parentComment = link.parentNode.parentNode.parentNode;

            // hide .comment-content
            parentComment.querySelector('.comment-content').style.display = 'none';

            // display .edit-comment-form
            parentComment.querySelector('.edit-comment-form').style.display = 'block';
        });
    });

    // Edit comment save submit
    document.querySelectorAll('.save-edit-comment').forEach(editCommentSaveBtn => {
        editCommentSaveBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // execute edit_comment function
            edit_comment(
                this.getAttribute('data-comment-id'),
                this.getAttribute('data-comment-text'), // old comment
                editCommentSaveBtn.parentNode.querySelector('.edit-comment-text').value // new comment
            );
        });
    });
    //cancel edit
    document.querySelectorAll('.cancel-edit-comment').forEach(editCommentSaveBtn => {
        editCommentSaveBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // execute edit_comment function
            edit_comment(
                this.getAttribute('data-comment-id'),
                this.getAttribute('data-comment-text'), // old comment
            );
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


function edit_comment(commentId, oldComment, newComment) {

    if (newComment !== null && newComment !== oldComment) {
        fetch('update_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `comment_id=${commentId}&new_text=${encodeURIComponent(newComment)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
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
