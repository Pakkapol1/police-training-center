// Enhanced Action Buttons JavaScript

// Initialize action buttons
document.addEventListener('DOMContentLoaded', function() {
    initializeActionButtons();
    initializeTooltips();
    initializeLoadingStates();
});

// Initialize action buttons functionality
function initializeActionButtons() {
    // Add click effects to action buttons
    document.querySelectorAll('.btn-action').forEach(button => {
        button.addEventListener('click', function(e) {
            // Add ripple effect
            createRippleEffect(this, e);
            
            // Add loading state for delete buttons
            if (this.classList.contains('btn-action-delete')) {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 2000);
            }
        });
        
        // Add hover sound effect (optional)
        button.addEventListener('mouseenter', function() {
            playHoverSound();
        });
    });
    
    // Initialize dropdown buttons
    document.querySelectorAll('.btn-action-dropdown').forEach(button => {
        button.addEventListener('click', function(e) {
            createRippleEffect(this, e);
        });
    });
}

// Create ripple effect on button click
function createRippleEffect(button, event) {
    const ripple = document.createElement('span');
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple');
    
    button.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// Play hover sound (optional)
function playHoverSound() {
    // Create audio context for hover sound
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.exponentialRampToValueAtTime(600, audioContext.currentTime + 0.1);
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    } catch (e) {
        // Audio not supported or blocked
        console.log('Audio not supported');
    }
}

// Initialize tooltips
function initializeTooltips() {
    // Custom tooltip implementation
    document.querySelectorAll('[title]').forEach(element => {
        if (element) {
            element.addEventListener('mouseenter', function(e) {
                showCustomTooltip(this, e);
            });
            
            element.addEventListener('mouseleave', function() {
                hideCustomTooltip();
            });
        }
    });
}

// Show custom tooltip
function showCustomTooltip(element, event) {
    const tooltip = document.createElement('div');
    tooltip.className = 'custom-tooltip';
    tooltip.textContent = element.getAttribute('title');
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
    
    setTimeout(() => {
        tooltip.classList.add('show');
    }, 10);
}

// Hide custom tooltip
function hideCustomTooltip() {
    const tooltip = document.querySelector('.custom-tooltip');
    if (tooltip) {
        tooltip.classList.remove('show');
        setTimeout(() => {
            tooltip.remove();
        }, 200);
    }
}

// Initialize loading states
function initializeLoadingStates() {
    // Add loading state to buttons that perform async operations
    document.querySelectorAll('.btn-action').forEach(button => {
        if (button && (button.onclick || button.href)) {
            button.addEventListener('click', function() {
                if (!this.classList.contains('btn-action-delete')) {
                    this.classList.add('loading');
                    setTimeout(() => {
                        this.classList.remove('loading');
                    }, 1000);
                }
            });
        }
    });
}

// Enhanced delete confirmation
function confirmDelete(id, title) {
    // Create custom confirmation modal
    const modal = document.createElement('div');
    modal.className = 'custom-confirm-modal';
    modal.innerHTML = `
        <div class="confirm-content">
            <div class="confirm-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4>ยืนยันการลบ</h4>
            <p>คุณต้องการลบประกาศ "${title}" ใช่หรือไม่?</p>
            <p class="warning-text">การดำเนินการนี้ไม่สามารถยกเลิกได้</p>
            <div class="confirm-buttons">
                <button class="btn btn-secondary cancel-btn">ยกเลิก</button>
                <button class="btn btn-danger confirm-btn">ลบ</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add event listeners
    modal.querySelector('.cancel-btn').addEventListener('click', () => {
        modal.remove();
    });
    
    modal.querySelector('.confirm-btn').addEventListener('click', () => {
        modal.remove();
        window.location.href = `/admin?action=announcements&sub_action=delete&id=${id}`;
    });
    
    // Close on backdrop click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    // Add animation
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}

// Enhanced preview function
function previewAnnouncement(id) {
    // Show loading state
    const button = event.target.closest('.btn-action-preview');
    if (button) {
        button.classList.add('loading');
    }
    
    // Open preview in new window
    const previewWindow = window.open(`/?page=announcements&action=detail&id=${id}`, '_blank');
    
    // Remove loading state after window opens
    setTimeout(() => {
        if (button) {
            button.classList.remove('loading');
        }
    }, 1000);
}

// Add CSS for custom elements
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .custom-tooltip {
        position: fixed;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        z-index: 1000;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.2s ease;
        pointer-events: none;
    }
    
    .custom-tooltip.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .custom-confirm-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .custom-confirm-modal.show {
        opacity: 1;
    }
    
    .confirm-content {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .custom-confirm-modal.show .confirm-content {
        transform: scale(1);
    }
    
    .confirm-icon {
        font-size: 3rem;
        color: #ffc107;
        margin-bottom: 1rem;
    }
    
    .confirm-content h4 {
        margin-bottom: 1rem;
        color: #495057;
    }
    
    .confirm-content p {
        margin-bottom: 0.5rem;
        color: #6c757d;
    }
    
    .warning-text {
        color: #dc3545;
        font-weight: 600;
    }
    
    .confirm-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }
    
    .confirm-buttons .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .confirm-buttons .btn:hover {
        transform: translateY(-2px);
    }
`;
document.head.appendChild(style); 