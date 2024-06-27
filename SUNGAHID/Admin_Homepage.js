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
});