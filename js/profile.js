document.addEventListener('DOMContentLoaded', function() {

//-------THIS WAS USED FOR TESTING PURPOSES ONLY-------//

//     const editProfileBtn = document.querySelector('.btn-edit-profile');
//     if (editProfileBtn) {
//         editProfileBtn.addEventListener('click', function() {
//             alert('Edit profile functionality coming soon!');
//         });
//     }
    
//     const editPhotoBtn = document.querySelector('.edit-photo-btn');
//     if (editPhotoBtn) {
//         editPhotoBtn.addEventListener('click', function() {
//             alert('Photo upload functionality coming soon!');
//         });
//     }
    
//     const settingsBtn = document.querySelector('.btn-settings');
//     if (settingsBtn) {
//         settingsBtn.addEventListener('click', function() {
//             alert('Settings page coming soon!');
//         });
//     }
    
//     const actionButtons = document.querySelectorAll('.action-btn');
//     actionButtons.forEach(button => {
//         button.addEventListener('click', function(e) {
//             if (!this.getAttribute('href')) {
//                 e.preventDefault();
//                 const text = this.querySelector('.action-text').textContent;
//                 alert(`${text} functionality coming soon!`);
//             }
//         });
//     });
    
    const recentItems = document.querySelectorAll('.recent-item');
    recentItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
    
    const yearSpan = document.querySelector('footer .footer-bottom p');
    if (yearSpan) {
        const currentYear = new Date().getFullYear();
        yearSpan.textContent = yearSpan.textContent.replace('2025', currentYear);
    }


 const editProfileBtn = document.querySelector('.btn-edit-profile');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            window.location.href = 'editProfile.php';
        });
    }
    
    // HANDLE THE EDIT PHOTO BUTTON
    const editPhotoBtn = document.querySelector('.edit-photo-btn');
    if (editPhotoBtn) {
        editPhotoBtn.addEventListener('click', function() {
            window.location.href = 'editProfile.php';
        });
    }
    
    // EDIT PROFILE BUTTON IN BIO SECTION
    const editProfileBtn2 = document.getElementById('edit-profile-btn');
    if (editProfileBtn2) {
        editProfileBtn2.addEventListener('click', function() {
            window.location.href = 'editProfile.php';
        });
    }
    
    // ADD BIO LINK
    const editBioLink = document.querySelector('.edit-link');
    if (editBioLink) {
        editBioLink.addEventListener('click', function(e) {
            e.preventDefault(); // Stop default link behavior
            window.location.href = 'editProfile.php';
        });
    }

    

});




