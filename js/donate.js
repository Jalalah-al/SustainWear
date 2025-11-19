document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const imageUpload = document.getElementById('imageUpload');
    const imagePreview = document.getElementById('imagePreview');
    const donationForm = document.getElementById('donationForm');

    
    uploadArea.addEventListener('click', () => {
        imageUpload.click();
    });

    imageUpload.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    createPreviewItem(e.target.result, file.name);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function createPreviewItem(src, filename) {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        
        const img = document.createElement('img');
        img.src = src;
        img.alt = filename;
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-image';
        removeBtn.innerHTML = '×';
        removeBtn.onclick = function() {
            previewItem.remove();
        };
        
        previewItem.appendChild(img);
        previewItem.appendChild(removeBtn);
        imagePreview.appendChild(previewItem);
    }

    
    donationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        
        const clothingType = document.getElementById('clothingType').value;
        const condition = document.getElementById('condition').value;
        const description = document.getElementById('description').value;
        
        if (!clothingType || !condition || !description) {
            alert('Please fill in all required fields (marked with *)');
            return;
        }

    
        const formData = new FormData(this);
        
        fetch('submit_donation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Thank you for your donation! Your items have been submitted.');
                donationForm.reset();
                imagePreview.innerHTML = '';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting your donation.');
        });
    });
});