$(document).ready(function() {
    var submitPost =document.getElementById('submitPost');
    var postContent =document.getElementById('postContent');
    var postAudience =document.getElementById('postAudience');
    var postImage = document.getElementById('postImage');
    var isImportantToggle = document.getElementById('importantToggle');

    var selectedImage = null; // Store the selected image data URL

    $('#postInput').on('click', function(e) {
        e.stopPropagation();
        openPostModal(true);
    });

    $('#postCloseModal').on('click', function() {
        openPostModal(false);
    });

    // closes post modal when click outside the modal
    $(document).on('click', function(event) {
        if ($(event.target).closest('.postmodal-content').length === 0 ) {
            openPostModal(false);
        }
    });

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
            xhr.open("POST", "create_post.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    postContent.innerHTML = ''; // Clear text content
                    postImage.value = ''; // Clear file input

                    // close modal 
                    openPostModal(false);
                    window.location.reload();
                }
            };
            xhr.send(formData);
        }
    }

    function openPostModal(openModal = true) {
        var postModal = $('#postModal');

        if(openModal) {
            postModal.show();
        } else {
            postModal.hide();
        }  
    }
});


