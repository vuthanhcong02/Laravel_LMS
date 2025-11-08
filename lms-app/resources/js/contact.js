document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const successMessage = document.getElementById('success-message');

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Simulate form submission
        setTimeout(() => {
            // Show success message
            successMessage.classList.add('show');

            // Reset form
            contactForm.reset();

            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
        }, 1000);
    });
});