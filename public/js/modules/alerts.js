export function initAlerts() {
    // === Auto-remove flash messages ===
    const flashes = document.querySelectorAll('.flash-message');
    flashes.forEach(function (flash) {
        setTimeout(function () {
            flash.style.display = 'none';
        }, 3500);
    });
}

// Attach to window so it can be called globally, similar to inline script
export function showToast(message, type = 'success') {
    let container = document.querySelector('.flash-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'flash-container';
        document.body.insertBefore(container, document.body.firstChild);
    }
    
    const toast = document.createElement('div');
    toast.className = 'flash-message alert-' + type;
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    toast.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
    
    toast.style.transition = 'opacity 0.3s ease';
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Make sure it's globally available
window.showToast = showToast;
