document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step');
    let currentStep = 1;

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('password-strength');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        passwordStrength.className = 'password-strength ';
        if (strength <= 2) {
            passwordStrength.classList.add('strength-weak');
        } else if (strength === 3) {
            passwordStrength.classList.add('strength-fair');
        } else if (strength === 4) {
            passwordStrength.classList.add('strength-good');
        } else {
            passwordStrength.classList.add('strength-strong');
        }
    });

    // Toggle password visibility
    document.getElementById('toggle-password').addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggle-confirm-password').addEventListener('click', function() {
        const confirmPassword = document.getElementById('confirmPassword');
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // Step navigation
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            goToStep(currentStep - 1);
        });
    });

    function goToStep(step) {
        // Hide current step
        document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('hidden');
        document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');

        // Show new step
        document.querySelector(`.form-step[data-step="${step}"]`).classList.remove('hidden');
        document.querySelector(`.step[data-step="${step}"]`).classList.add('active');

        currentStep = step;
    }

    function validateStep(step) {
        let isValid = true;

        if (step === 1) {
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const email = document.getElementById('email').value;

            if (!firstName || !lastName || !email) {
                showError('Vui lòng điền đầy đủ thông tin cá nhân');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('Email không hợp lệ');
                isValid = false;
            }
        } else if (step === 2) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!username || username.length < 4) {
                showError('Tên đăng nhập phải có ít nhất 4 ký tự');
                isValid = false;
            } else if (!password || password.length < 8) {
                showError('Mật khẩu phải có ít nhất 8 ký tự');
                isValid = false;
            } else if (password !== confirmPassword) {
                showError('Mật khẩu xác nhận không khớp');
                isValid = false;
            }
        }

        if (!isValid) {
            errorMessage.classList.remove('hidden');
            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateStep(3)) return;

        if (!document.getElementById('terms').checked) {
            showError('Vui lòng đồng ý với Điều khoản dịch vụ và Chính sách bảo mật');
            return;
        }

        // Simulate registration
        simulateRegistration();
    });

    function simulateRegistration() {
        const submitBtn = registerForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
        submitBtn.disabled = true;

        setTimeout(() => {
            successMessage.classList.remove('hidden');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            // Redirect to login after 3 seconds
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 3000);
        }, 2000);
    }

    function showError(message) {
        document.getElementById('error-text').textContent = message;
        errorMessage.classList.remove('hidden');

        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
    }

    // Social register handlers
    document.querySelector('.google-btn').addEventListener('click', function() {
        alert('Chức năng đăng ký bằng Google đang được phát triển');
    });

    document.querySelector('.facebook-btn').addEventListener('click', function() {
        alert('Chức năng đăng ký bằng Facebook đang được phát triển');
    });

});