export function initSearch() {
    // === Live Search Suggestions & History ===
    const searchForm = document.querySelector('.search-bar');
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const searchClearBtn = document.getElementById('searchClearBtn');
    let searchTimeout;

    if (searchInput && searchSuggestions && searchForm) {

        // Clear button click
        if (searchClearBtn) {
            searchClearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                searchInput.focus();
                this.style.display = 'none';
                searchSuggestions.style.display = 'none';
            });
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (searchClearBtn) {
                searchClearBtn.style.display = query.length > 0 ? 'block' : 'none';
            }
            
            if (query.length < 1) {
                searchSuggestions.style.display = 'none';
                return;
            }

            // Debounce for 300ms
            searchTimeout = setTimeout(() => {
                const searchUrl = searchForm.getAttribute('action');
                const ajaxUrl = searchUrl + '/ajax?q=' + encodeURIComponent(query);
                const baseUrl = searchUrl.replace(/\/search$/, '');
                
                fetch(ajaxUrl)
                    .then(response => response.json())
                    .then(data => {
                        searchSuggestions.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(book => {
                                const imgSrc = book.cover_image ? 
                                    (book.cover_image.startsWith('/') ? baseUrl + book.cover_image : baseUrl + '/images/books/' + book.cover_image) : 
                                    'https://via.placeholder.com/40x55?text=Img';
                                
                                const html = `
                                    <a href="${baseUrl}/book/${book.book_id}" class="search-suggestion-item">
                                        <img src="${imgSrc}" class="search-suggestion-img" alt="${book.title}">
                                        <div class="search-suggestion-info">
                                            <div class="search-suggestion-title">${book.title}</div>
                                        </div>
                                    </a>
                                `;
                                searchSuggestions.innerHTML += html;
                            });
                            searchSuggestions.style.display = 'block';
                        } else {
                            searchSuggestions.innerHTML = '<div style="padding:15px; color:#666; text-align:center;">Không tìm thấy kết quả</div>';
                            searchSuggestions.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        console.error("Lỗi tìm kiếm:", err);
                    });
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.style.display = 'none';
            }
        });

        // Show suggestions when focusing on input if not empty
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0 && searchSuggestions.innerHTML !== '') {
                searchSuggestions.style.display = 'block';
            }
        });
    }
}
