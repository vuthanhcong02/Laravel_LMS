document.addEventListener('DOMContentLoaded', function() {

    const registerForm = document.getElementById('register-form');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    // Form submission
    registerForm.addEventListener("submit", async function(e) {
        e.preventDefault();

        if (!document.getElementById("terms").checked) {
            showError("Vui lòng đồng ý với Điều khoản dịch vụ và Chính sách bảo mật");
            return;
        }

        const submitBtn = registerForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(registerForm);
            console.log("🚀 Submitting form data:", Object.fromEntries(formData.entries()));
            const response = await fetch("register", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            if (response.ok) {
                successMessage.classList.remove("hidden");
                errorMessage.classList.add("hidden");

                setTimeout(() => {
                    window.location.href = "/";
                }, 2000);
            } else {
                const errData = await response.json();

                const message =
                    errData.message ||
                    (errData.errors ?
                        Object.values(errData.errors).flat().join(", ") :
                        "Có lỗi xảy ra khi đăng ký");
                showError(message);
            }
        } catch (error) {
            console.error("🚨 Fetch error:", error);
            showError("Không thể kết nối đến máy chủ!");
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

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