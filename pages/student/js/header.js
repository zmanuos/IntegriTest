document.addEventListener('DOMContentLoaded', () => {
    const profilePic = document.getElementById('profile-pic');
    const profileIconContainer = document.getElementById('profile-icon-container');
    const profilePopup = document.getElementById('profile-popup');
    
    profilePic.addEventListener('click', (event) => {
        event.stopPropagation();
        profilePopup.classList.toggle('show');
    });

    profileIconContainer.addEventListener('click', (event) => {
        event.stopPropagation();
        profilePopup.classList.toggle('show');
    });

    document.addEventListener('click', (event) => {
        if (!profilePopup.contains(event.target) && event.target !== profilePic && event.target !== profileIconContainer) {
            profilePopup.classList.remove('show');
        }
    });
});

function redirectToProfile() {
    window.location.href = "profile.php";
}