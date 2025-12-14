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



// -----This didn't work so I rewrote it better below-----//// 
// function viewDonationRequests(){

//     let drf = document.getElementById("donationsReviewForm");
//     let sd = document.querySelector(".donate-sidebar");
//     let hc = document.querySelector(".history-controls");
//     let filtered = document.getElementById("filterDonationsSection");
//     document.getElementById("viewDonationRequests").innerHTML="Back";

//     filtered.style.display="none";
//     drf.style.display="block";
//     sd.style.display= "flex";
//     sd.style.flexDirection= "column";
//     sd.style.gap= "1.5rem";
//     hc.style.display="none";

// }


function viewDonationRequests() {
    let drf = document.getElementById("donationsReviewForm");
    let sd = document.querySelector(".donate-sidebar");
    let hc = document.querySelector(".history-controls");
    let filtered = document.getElementById("filterDonationsSection");
    let button = document.getElementById("viewDonationRequests");

    const buttonText = button.textContent || button.innerText;
    
    if (buttonText.includes("View Donation Requests")) {
        button.innerHTML = "Back";
        filtered.style.display = "none";
        drf.style.display = "block";
        sd.style.display = "flex";
        sd.style.flexDirection = "column";
        sd.style.gap = "1.5rem";
        hc.style.display = "none";
    } else {
        button.innerHTML = "View Donation Requests";
        filtered.style.display = "block"; 
        drf.style.display = "none"; 
        
        sd.style.display = ""; 
        sd.style.flexDirection = ""; 
        sd.style.gap = ""; 
        
       
        hc.style.display = "flex";
    }
}



// function filterDonations() {
//     const searchInput = document.getElementById('search-input');
//     const typeFilter = document.getElementById('type-filter');
//     const donationBoxes = document.querySelectorAll('.donation-box');
    
//     const searchTerm = searchInput.value.toLowerCase().trim();
//     const selectedType = typeFilter.value;
    
//     let visibleCount = 0;
    
//     donationBoxes.forEach(box => {
//         // get specific data from the donation box
//         const donationId = box.querySelector('h4')?.textContent || '';
//         const clothingType = box.querySelector('.detail:nth-child(2)')?.textContent || '';
//         const condition = box.querySelector('.detail:nth-child(1)')?.textContent || '';
//         const description = box.querySelector('.description p')?.textContent || '';
//         const date = box.querySelector('.detail:nth-child(3)')?.textContent || '';
        
//         // check type filter
//         const typeMatch = selectedType === 'all' || 
//                          clothingType.toLowerCase().includes(selectedType);
        
//         // check search term 
//         let searchMatch = true;
//         if (searchTerm) {
//             searchMatch = donationId.toLowerCase().includes(searchTerm) ||
//                          clothingType.toLowerCase().includes(searchTerm) ||
//                          condition.toLowerCase().includes(searchTerm) ||
//                          description.toLowerCase().includes(searchTerm) ||
//                          date.toLowerCase().includes(searchTerm);
//         }
        
//         // show or hide based on filters
//         if (typeMatch && searchMatch) {
//             box.style.display = 'block';
//             box.classList.remove('hidden');
//             visibleCount++;
//         } else {
//             box.style.display = 'none';
//             box.classList.add('hidden');
//         }
//     });
    
//     // ---- show message if no results--////
//     const container = document.querySelector('.approved-donations-container');
//     let noResultsMsg = container.querySelector('.no-results-message');
    
//     if (visibleCount === 0) {
//         if (!noResultsMsg) {
//             noResultsMsg = document.createElement('div');
//             noResultsMsg.className = 'no-results-message';
//             noResultsMsg.textContent = 'No donations match your filters.';
//             container.appendChild(noResultsMsg);
//         }
//     } else if (noResultsMsg) {
//         noResultsMsg.remove();
//     }
// }

// // ---EVENT LISTNER WITH ENTER KEY SUPPORTS----///
// document.addEventListener('DOMContentLoaded', function() {
//     const searchInput = document.getElementById('search-input');
//     const typeFilter = document.getElementById('type-filter');
//     const searchBtn = document.querySelector('.search-btn');
    
//     if (searchInput) {
//         searchInput.addEventListener('input', filterDonations);
//         searchInput.addEventListener('keypress', function(e) {
//             if (e.key === 'Enter') {
//                 filterDonations();
//             }
//         });
//     }
    
//     if (typeFilter) {
//         typeFilter.addEventListener('change', filterDonations);
//     }
    
//     if (searchBtn) {
//         searchBtn.addEventListener('click', filterDonations);
//     }
    
   
//     filterDonations();
// });



// const clearFiltersBtn = document.getElementById('clear-filters');
// if (clearFiltersBtn) {
//     clearFiltersBtn.addEventListener('click', function() {
//         document.getElementById('search-input').value = '';
//         document.getElementById('type-filter').value = 'all';
//         filterDonations();
//     });
// }

    