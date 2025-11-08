document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    // Form submission handling
    loginForm.addEventListener('submit', async(e) => {
        e.preventDefault();

        const email = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const remember = document.getElementById('remember').checked;
        const token = document.querySelector('meta[name="csrf-token"]').content;

        const submitBtn = loginForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
        submitBtn.disabled = true;

        if (!email || !password) {
            errorText.textContent = "Vui lòng điền đầy đủ thông tin đăng nhập";
            errorMessage.classList.remove('hidden');
            return;
        }

        try {
            const res = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password, remember })
            });

            if (res.ok) {
                successMessage.classList.remove("hidden");
                errorMessage.classList.add("hidden");

                setTimeout(() => {
                    window.location.href = "/";
                }, 2000);
            } else {
                const errData = await res.json();
                const message = errData.message ||
                    (errData.errors ?
                        Object.values(errData.errors).flat().join(", ") :
                        "Sai tài khoản hoặc mật khẩu");
                showError(message);
            }
        } catch (err) {
            console.error(err);
            showError("Không thể kết nối đến máy chủ!");
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    function showError(message) {
        document.getElementById('error-text').textContent = message;
        errorMessage.classList.remove('hidden');

        // Auto hide error after 5 seconds
        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
    }

    // Cart functionality
    let cartCount = localStorage.getItem('cartCount') || 0;
    document.querySelector('.cart-count').textContent = cartCount;
});