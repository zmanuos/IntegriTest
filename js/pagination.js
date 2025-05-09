const searchTerm = new URLSearchParams(window.location.search).get('search');
    
if (searchTerm) {
    document.getElementById('pagination-container').style.display = 'none';
}