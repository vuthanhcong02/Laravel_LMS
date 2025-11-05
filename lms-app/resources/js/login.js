document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // Form submission handling
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember').checked;

        // Simple validation
        if (!username || !password) {
            showError('Vui lòng điền đầy đủ thông tin đăng nhập');
            return;
        }

        // Simulate login process
        simulateLogin(username, password, remember);
    });

    function simulateLogin(username, password, remember) {
        // Show loading state
        const submitBtn = loginForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang đăng nhập...';
        submitBtn.disabled = true;

        // Simulate API call
        setTimeout(() => {
            // Mock login success for demo
            // In real app, you would validate against your backend
            if (username === 'demo' && password === 'demo') {
                // Login successful
                window.location.href = 'index.html';
            } else {
                // Login failed
                showError('Tên đăng nhập hoặc mật khẩu không đúng');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 1500);
    }

    function showError(message) {
        document.getElementById('error-text').textContent = message;
        errorMessage.classList.remove('hidden');

        // Auto hide error after 5 seconds
        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
    }

    // Social login handlers
    document.querySelector('.google-btn').addEventListener('click', function() {
        alert('Chức năng đăng nhập bằng Google đang được phát triển');
    });

    document.querySelector('.facebook-btn').addEventListener('click', function() {
        alert('Chức năng đăng nhập bằng Facebook đang được phát triển');
    });

    // Cart functionality
    let cartCount = localStorage.getItem('cartCount') || 0;
    document.querySelector('.cart-count').textContent = cartCount;
});