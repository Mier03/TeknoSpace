const modal = document.getElementById("manageAccountModal");
        const manageAccountLink = document.querySelector('.manage-account');
        const closeBtn = document.querySelector('.modal-content .close');

        manageAccountLink.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = "block";
        });

        closeBtn.addEventListener('click', function () {
            modal.style.display = "none"; 
        });

        window.addEventListener('click', function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        function goToAllAccounts() {
            modal.style.display = "none";
            window.location.href = "allAccounts.php"; 
        }

        function goToVerifyAccounts() {
            modal.style.display = "none";
            window.location.href = "verify.php"; 
        }

        const postModal = document.getElementById("postModal");
        const postModalCloseBtn = document.querySelector('#postModal .close');

        function openPostModal() {
            postModal.style.display = "block";
        }

        function closePostModal() {
            postModal.style.display = "none"; 
        }

        postModalCloseBtn.addEventListener('click', closePostModal);

        window.addEventListener('click', function (event) {
            if (event.target == postModal) {
                postModal.style.display = "none"; 
            }
        });
        
        function showLogoutModal() {
            var modal = document.getElementById('logoutModal');
            modal.style.display = "block";
            
            setTimeout(function() {
                modal.style.display = "none";
                window.location.href = "../index.php";
            }, 1500);
        }