document.addEventListener('DOMContentLoaded', function() {
    var postInput = document.getElementById('postInput');
    var modal = document.getElementById('postModal');
    var span = document.getElementsByClassName('close')[0];
    var submitPost = document.getElementById('submitPost');
    var postContent = document.getElementById('postContent');
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
        var post = 'Maintenance';

        if (content) {
            var formData = new FormData();
            formData.append("content", content);
            formData.append("audience", audience);
            formData.append("posts", post);
            if (imageFile) {
                formData.append("image", imageFile);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../Users/create_post.php", true);
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
    
});
