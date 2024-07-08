document.addEventListener('DOMContentLoaded', function() {
    var postInput = document.getElementById('postInput');
    var modal = document.getElementById('postModal');
    var span = document.getElementsByClassName('close')[0];
    var submitPost = document.getElementById('submitPost');
    var postContent = document.getElementById('postContent');
    var posttype = document.getElementById('postType');
    var postAudience = document.getElementById('postAudience');
    var postImage = document.getElementById('postImage');
    
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
        var post = posttype.value;

        if (content) {
            var formData = new FormData();
            formData.append("content", content);
            formData.append("audience", audience);
            formData.append("posts", post);
            if (imageFile) {
                formData.append("image", imageFile);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "create_post.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    postContent.value = '';
                    postImage.value = '';
                    modal.style.display = "none";
                    window.location.reload();
                }
            };
            xhr.send(formData);
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
                // Create an img element and set its src to the image data
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '300px';
                img.alt = 'Selected Image';
                
                // Insert the img element into the post content area
                postContent.value = '';
                postContent.appendChild(img);
            };
            reader.readAsDataURL(postImage.files[0]);
        }
    });
});
