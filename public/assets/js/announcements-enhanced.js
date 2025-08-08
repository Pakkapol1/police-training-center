// Enhanced Announcements Page JavaScript



// Image Modal functionality
function showImageModal(imageUrl, title) {
    document.getElementById('imageModalTitle').textContent = title;
    const modalImage = document.getElementById('modalImage');
    
    // Show loading state
    modalImage.style.opacity = '0.5';
    modalImage.src = `/uploads/announcements/${imageUrl}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
    
    // Restore image opacity when loaded
    modalImage.onload = function() {
        this.style.opacity = '1';
    };
    
    // Handle image load error
    modalImage.onerror = function() {
        this.src = '/assets/img/placeholder-image.jpg';
        this.style.opacity = '1';
    };
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Simplified search functionality
let searchTimeout;
function initializeEnhancedSearch() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = this.value;
            
            // Show loading indicator
            const searchIcon = document.querySelector('.search-icon-wrapper i');
            if (searchIcon) {
                searchIcon.className = 'fas fa-spinner fa-spin';
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
            }, 800);
        });
    }
}

// Perform search function
function performSearch(searchTerm) {
    console.log('Performing search for:', searchTerm); // Debug log
    
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('search', searchTerm);
    currentUrl.searchParams.set('p', '1');
    
    // Show loading state
    document.body.style.cursor = 'wait';
    
    // Show notification
    showNotification('กำลังค้นหา...', 'info');
    
    // Navigate to search results
    window.location.href = currentUrl.toString();
}



// Simplified filter functionality
function initializeEnhancedFilters() {
    const filters = ['statusFilter'];
    
    filters.forEach(filterId => {
        const filter = document.getElementById(filterId);
        if (filter) {
            filter.addEventListener('change', function() {
                showFilterLoading();
                const value = this.value;
                const currentUrl = new URL(window.location);
                
                if (value === 'all') {
                    currentUrl.searchParams.delete(filterId.replace('Filter', ''));
                } else {
                    currentUrl.searchParams.set(filterId.replace('Filter', ''), value);
                }
                currentUrl.searchParams.set('p', '1');
                
                window.location.href = currentUrl.toString();
            });
        }
    });
}

// Show loading state for filters
function showFilterLoading() {
    document.body.style.cursor = 'wait';
    showNotification('กำลังกรองข้อมูล...', 'info');
}

// Enhanced clear filters
function clearFilters() {
    const currentUrl = new URL(window.location);
    const hasFilters = currentUrl.searchParams.has('search') || 
                      currentUrl.searchParams.has('status') || 
                      currentUrl.searchParams.has('priority') || 
                      currentUrl.searchParams.has('sort');
    
    if (hasFilters) {
        if (confirm('คุณต้องการล้างตัวกรองทั้งหมดใช่หรือไม่?')) {
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.delete('status');
            currentUrl.searchParams.delete('priority');
            currentUrl.searchParams.delete('sort');
            currentUrl.searchParams.set('p', '1');
            window.location.href = currentUrl.toString();
        }
    } else {
        showNotification('ไม่มีตัวกรองที่ต้องล้าง', 'info');
    }
}

// View toggle functionality
function initializeViewToggle() {
    const viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(button => {
        if (button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(btn => {
                    if (btn) btn.classList.remove('active');
                });
                this.classList.add('active');
                const targetView = this.dataset.view;
                
                // Hide all views
                const tableView = document.getElementById('tableView');
                const gridView = document.getElementById('gridView');
                if (tableView) tableView.style.display = 'none';
                if (gridView) gridView.style.display = 'none';
                
                // Show target view with animation
                const targetElement = document.getElementById(targetView);
                if (targetElement) {
                    targetElement.style.display = 'block';
                    targetElement.style.opacity = '0';
                    targetElement.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        targetElement.style.transition = 'all 0.3s ease';
                        targetElement.style.opacity = '1';
                        targetElement.style.transform = 'translateY(0)';
                    }, 50);
                }
            });
        }
    });
}

// Enhanced animations
function initializeEnhancedAnimations() {
    // Add hover effects to stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        if (card) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        }
    });
    
    // Add click effects to announcement cards
    document.querySelectorAll('.announcement-card').forEach(card => {
        if (card) {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown') && !e.target.closest('.card-image')) {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                }
            });
        }
    });
}

// Initialize all enhanced features
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing enhanced features...'); // Debug log
    
    initializeEnhancedSearch();
    initializeEnhancedFilters();
    initializeViewToggle();
    initializeEnhancedAnimations();
    
    // Test search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        console.log('Search input found:', searchInput); // Debug log
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch(this.value);
            }
        });
    } else {
        console.log('Search input not found!'); // Debug log
    }
    
    // Add loading animation delays
    document.querySelectorAll('.announcement-row').forEach((row, index) => {
        if (row) row.style.animationDelay = `${index * 0.1}s`;
    });
    
    document.querySelectorAll('.announcement-card').forEach((card, index) => {
        if (card) card.style.animationDelay = `${index * 0.1}s`;
    });
    
    document.querySelectorAll('.stat-card').forEach((card, index) => {
        if (card) card.style.animationDelay = `${index * 0.1}s`;
    });
}); 