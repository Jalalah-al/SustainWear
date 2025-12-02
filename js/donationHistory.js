document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const typeFilter = document.getElementById('type-filter');
    const donationItems = document.querySelectorAll('.donation-item');
    

    showAllItems();
    
    function filterDonations() {
        const searchValue = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;
        
        donationItems.forEach(item => {
            const itemType = item.getAttribute('data-type');
            const itemText = item.textContent.toLowerCase();
            
            const matchesType = typeValue === 'all' || itemType === typeValue;
            const matchesSearch = searchValue === '' || itemText.includes(searchValue);
            
            if (matchesType && matchesSearch) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
        
        updateVisibleCount();
    }
    
    function showAllItems() {
        donationItems.forEach(item => {
            item.style.display = 'flex';
        });
        updateVisibleCount();
    }
    
    function updateVisibleCount() {
        const visibleItems = document.querySelectorAll('.donation-item[style="display: flex"]');
        console.log(`Showing ${visibleItems.length} of ${donationItems.length} donations`);
    }
    
    searchInput.addEventListener('input', filterDonations);
    typeFilter.addEventListener('change', filterDonations);
    
    searchInput.value = '';
});