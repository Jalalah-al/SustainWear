document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const typeFilter = document.getElementById('type-filter');
    const searchBtn = document.querySelector('.search-btn');
    const donationBoxes = document.querySelectorAll('.donation-box');
    const noResultsMessage = document.getElementById('no-results-message');
    const approvedContainer = document.getElementById('approved-donations-container');

    // Function to filter donations
    function filterDonations() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedType = typeFilter.value;
        
        let visibleCount = 0;
        
        donationBoxes.forEach(box => {
            const boxType = box.getAttribute('data-type') || '';
            const boxDescription = box.getAttribute('data-description') || '';
            const boxCondition = box.getAttribute('data-condition') || '';
            const boxText = box.textContent.toLowerCase();
            
            // Check if matches search term
            const matchesSearch = searchTerm === '' || 
                                 boxText.includes(searchTerm) ||
                                 boxDescription.toLowerCase().includes(searchTerm) ||
                                 boxType.includes(searchTerm) ||
                                 boxCondition.toLowerCase().includes(searchTerm) ||
                                 box.getAttribute('data-id').includes(searchTerm);
            
            // Check if matches type filter
            const matchesType = selectedType === 'all' || 
                               boxType.includes(selectedType) ||
                               selectedType === 't-shirt' && boxType.includes('shirt');
            
            // Show or hide the donation box
            if (matchesSearch && matchesType) {
                box.style.display = 'block';
                visibleCount++;
            } else {
                box.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterDonations);
    typeFilter.addEventListener('change', filterDonations);
    searchBtn.addEventListener('click', filterDonations);
    
    // Filter on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterDonations();
        }
    });
    
    // Initial filter to handle any initial values
    filterDonations();
});