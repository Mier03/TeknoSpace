document.addEventListener('DOMContentLoaded', function() {
    var postInput = document.getElementById('postInput');
    var modal = document.getElementById('postModal');
    var span = document.querySelector('#postModal .close');
    var submitPost = document.getElementById('submitPost');
    var postContent = document.getElementById('postContent');
    var postAudience = document.getElementById('postAudience');
    var postImage = document.getElementById('postImage');
    
    var isImportantToggle = document.getElementById('importantToggle');
    var selectedImage = null; // Store the selected image data URL

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

    // Select the picture icon and the hidden file input
    const pictureIcon = document.querySelector('.fi.fi-br-picture');

    // Add click event listener to the picture icon
    pictureIcon.addEventListener('click', function() {
        // Trigger a click on the hidden file input
        postImage.click();
    });

    // Add event listener to handle the file selection
    postImage.addEventListener('change', function() {
        if (postImage.files && postImage.files[0]) {
            // Create a FileReader to read the selected image file
            var reader = new FileReader();
            reader.onload = function(e) {
                // Store the selected image data
                selectedImage = e.target.result;

                // Create an img element and set its src to the image data
                var img = document.createElement('img');
                img.src = selectedImage;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '300px';
                img.alt = 'Selected Image';

                // Clear the post content area and insert the img element
                
                postContent.appendChild(img);
            };
            reader.readAsDataURL(postImage.files[0]);
        }
    });

    submitPost.onclick = function() {
        var textContent = postContent.innerText; // Get text content
        var audience = postAudience.value;
        var imageFile = postImage.files[0];
        var post = 'Announcement';

        if (textContent || imageFile) {
            var formData = new FormData();
            formData.append("content", textContent); // Append text content
            formData.append("audience", audience);
            formData.append("posts", post);
            formData.append("isImportant", isImportantToggle.checked ? 1 : 0);
            if (imageFile) {
                formData.append("image", imageFile);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Users/create_post.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    postContent.innerHTML = ''; // Clear text content
                    postImage.value = ''; // Clear file input
                    modal.style.display = "none";
                    window.location.reload();
                }
            };
            xhr.send(formData);
        }
    }
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
