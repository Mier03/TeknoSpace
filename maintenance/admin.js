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
            window.location.href = "../Users/allAccounts.php"; 
        }

        function goToVerifyAccounts() {
            modal.style.display = "none";
            window.location.href = "../Users/verify.php"; 
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

        /* Log  Out Modal Javascript*/

        document.addEventListener('DOMContentLoaded', function() {
            const notificationIcon = document.querySelector('a[href="#notif"] i');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotification = document.querySelector('.close-notification');
        
            notificationIcon.addEventListener('click', function(e) {
                e.preventDefault();
                notificationModal.style.display = 'block';
                this.classList.add('active');
                this.classList.remove('fi-br-bell-notification-social-media');
                this.classList.add('fi-br-cross-small');
            });
        
            closeNotification.addEventListener('click', function() {
                notificationModal.style.display = 'none';
                notificationIcon.classList.remove('active');
                notificationIcon.classList.remove('fi-br-cross-small');
                notificationIcon.classList.add('fi-br-bell-notification-social-media');
            });
        
            window.addEventListener('click', function(e) {
                if (e.target == notificationModal) {
                    notificationModal.style.display = 'none';
                    notificationIcon.classList.remove('active');
                    notificationIcon.classList.remove('fi-br-cross-small');
                    notificationIcon.classList.add('fi-br-bell-notification-social-media');
                }
            });
        });