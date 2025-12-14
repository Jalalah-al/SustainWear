
    function updateCharCount() {
        const bioTextarea = document.getElementById('bio');
        const charCount = document.getElementById('charCount');
        
        if (bioTextarea && charCount) {
            const currentLength = bioTextarea.value.length;
            charCount.textContent = currentLength;

            if (currentLength > 500) {
                bioTextarea.value = bioTextarea.value.substring(0, 500);
                charCount.textContent = 500;
            }
        }
    }
    

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('photoPreview');
        const currentPhoto = document.getElementById('currentPhoto');
        const photoPlaceholder = document.getElementById('photoPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
          
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                if (currentPhoto) {
                    currentPhoto.style.display = 'none';
                }
                if (photoPlaceholder) {
                    photoPlaceholder.style.display = 'none';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
 
    document.addEventListener('DOMContentLoaded', function() {
        updateCharCount();
        
       
        const preview = document.getElementById('photoPreview');
        if (preview && preview.src) {
            preview.style.display = 'block';
        }
    });