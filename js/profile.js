document.addEventListener('DOMContentLoaded', function() {

    const editProfileBtn = document.querySelector('.btn-edit-profile');
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function() {
            alert('Edit profile functionality coming soon!');
        });
    }
    
    const editPhotoBtn = document.querySelector('.edit-photo-btn');
    if (editPhotoBtn) {
        editPhotoBtn.addEventListener('click', function() {
            alert('Photo upload functionality coming soon!');
        });
    }
    
    const settingsBtn = document.querySelector('.btn-settings');
    if (settingsBtn) {
        settingsBtn.addEventListener('click', function() {
            alert('Settings page coming soon!');
        });
    }
    
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.getAttribute('href')) {
                e.preventDefault();
                const text = this.querySelector('.action-text').textContent;
                alert(`${text} functionality coming soon!`);
            }
        });
    });
    
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
});