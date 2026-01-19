

let searchTimeout;
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-keyword');
    const userTypeSelect = document.getElementById('user-type-select');
    
    if (searchInput && userTypeSelect) {
     
        searchInput.addEventListener('input', performLiveSearch);
        userTypeSelect.addEventListener('change', performLiveSearch);
        
        performLiveSearch();
    }
});


function performLiveSearch() {
    clearTimeout(searchTimeout);
    
    const keyword = document.getElementById('search-keyword').value;
    const userType = document.getElementById('user-type-select').value;
    
    searchTimeout = setTimeout(() => {
        searchUsers(keyword, userType);
    }, 300);
}

/*
 AJAX request to search users
 */
function searchUsers(keyword, userType) {
    const tableBody = document.querySelector('.user-table tbody');
    
    //  loading state
    tableBody.innerHTML = '<tr><td colspan="7" class="no-data">Searching...</td></tr>';
    
    //  form data
    const formData = new URLSearchParams();
    formData.append('keyword', keyword);
    formData.append('user_type', userType);
    
    // AJAX request
    fetch('../Controller/search_users_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        tableBody.innerHTML = html;
    })
    .catch(error => {
        console.error('Search error:', error);
        tableBody.innerHTML = '<tr><td colspan="7" class="no-data">Error loading results. Please try again.</td></tr>';
    });
}