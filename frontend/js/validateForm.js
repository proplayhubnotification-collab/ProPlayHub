// Toast notification system
let tryCount = 0;
function showToast(message, type = 'info', duration = 3000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-slide-in mb-3 p-4 rounded-lg shadow-lg max-w-sm w-full flex items-start gap-3';
    
    // Define colors based on type
    const types = {
        success: 'bg-white/20 text-green-500 backdrop-blur-sm text-white',
        error: 'bg-white/20 text-red-500 backdrop-blur-sm text-white',
        warning: 'bg-white/20 text-yellow-500 backdrop-blur-sm text-white',
        info: 'bg-white/20 text-blue-500 backdrop-blur-sm text-white'
    };
    
    // Define icons based on type
    const icons = {
        success: `<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>`,
        error: `<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`,
        warning: `<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                  </svg>`,
        info: `<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
               </svg>`
    };
    
    toast.className += ` ${types[type] || types.info}`;
    
    toast.innerHTML = `
        <div class="flex-shrink-0">
            ${icons[type] || icons.info}
        </div>
        <div class="flex-1">
            <p class="font-medium">${message}</p>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after duration
    setTimeout(() => {
        toast.classList.remove('toast-slide-in');
        toast.classList.add('toast-slide-out');
        tryCount = tryCount - 1;
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, duration);
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input, textarea, select');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
        } else {
            input.classList.remove('error');
        }
    });

    return isValid; 
}
// Handle login form submission
function handleLogin() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    
    // Validate inputs
    if (!username && !password) {
        if (tryCount < 3) {
            showToast('Please enter username and password', 'error');
            tryCount += 1;
        }
        return;
    }
    
    if (!username) {
        if (tryCount < 3) {
            showToast('Please enter your username', 'error');
            tryCount += 1;
        }
        return;
    }
    
    if (!password) {
        if (tryCount < 3) {
            showToast('Please enter your password', 'error');
            tryCount += 1;
        }
        return;
    }    
    // Simulate login process
    showToast('Logging in...', 'info', 2000);
    
    setTimeout(() => {
        // Simulate successful login (replace with actual API call)
        const success = Math.random() > 0.3; // 70% success rate for demo
        
        if (success) {
            showToast('Login successful! Redirecting...', 'success');
            // Redirect or perform action after successful login
            // window.location.href = '/dashboard';
        } else {
            showToast('Invalid username or password', 'error');
        }
    }, 2000);
}

// Test toast on page load (optional - remove in production)
window.addEventListener('DOMContentLoaded', () => {
    // Uncomment to test toast on page load
    // setTimeout(() => {
    //     showToast('Welcome to ProPlayHub!', 'success', 4000);
    // }, 500);
});