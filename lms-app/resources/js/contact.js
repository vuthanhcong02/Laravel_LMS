document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const successMessage = document.getElementById('success-message');
    const successText = successMessage.querySelector('p');
    const submitBtn = contactForm.querySelector('button[type="submit"]');

    if (!contactForm) return;

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('[id^="error-"]').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });

        // Change button state
        const originalBtnHtml = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Đang gửi...';
        submitBtn.disabled = true;

        const formData = new FormData(contactForm);

        fetch(contactForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                successText.textContent = data.message;
                successMessage.classList.remove('hidden');

                // Reset form
                contactForm.reset();

                // Hide success message after 5 seconds
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 5000);
            } else if (data.errors) {
                // Show inline validation errors
                for (const [field, messages] of Object.entries(data.errors)) {
                    const errorEl = document.getElementById(`error-${field}`);
                    const inputEl = document.getElementById(field);
                    
                    if (errorEl) {
                        errorEl.textContent = messages[0];
                        errorEl.classList.remove('hidden');
                    }
                    if (inputEl) {
                        inputEl.classList.add('border-red-500');
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi hệ thống, vui lòng thử lại sau.');
        })
        .finally(() => {
            // Restore button
            submitBtn.innerHTML = originalBtnHtml;
            submitBtn.disabled = false;
        });
    });
});