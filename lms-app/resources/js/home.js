AOS.init({
    duration: 800,
    once: true,
    offset: 100
});

// // Giỏ hàng đơn giản
// Thêm vào giỏ hàng

// Cart Popup functionality
document.addEventListener('DOMContentLoaded', function() {
    const cartPopup = document.getElementById('cart-popup');
    const cartButton = document.getElementById('cart-button');
    const closeCart = document.getElementById('close-cart');
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const paymentPopup = document.getElementById('payment-popup');
    const successPopup = document.getElementById('success-popup');
    const closePayment = document.getElementById('close-payment');
    const cancelPayment = document.getElementById('cancel-payment');
    const confirmPayment = document.getElementById('confirm-payment');
    const closeSuccess = document.getElementById('close-success');

    // Open cart popup
    cartButton.addEventListener('click', function() {
        cartPopup.classList.remove('hidden');
        updateCartPopup();
    });

    // Close cart popup
    closeCart.addEventListener('click', function() {
        cartPopup.classList.add('hidden');
    });

    // Close popup when clicking outside
    cartPopup.addEventListener('click', function(e) {
        if (e.target === cartPopup) {
            cartPopup.classList.add('hidden');
        }
    });

    // Update cart popup content
    function updateCartPopup() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];

        if (cart.length === 0) {
            cartItems.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                    <p>Giỏ hàng của bạn đang trống</p>
                </div>
            `;
            cartTotal.textContent = '0đ';
            return;
        }

        let total = 0;
        let itemsHTML = '';

        cart.forEach(item => {
            total += item.price;
            itemsHTML += `
                <div class="flex items-center border-b pb-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-md flex items-center justify-center mr-4">
                        <i class="fas fa-laptop-code text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold">${item.name}</h4>
                        <p class="text-indigo-600 font-bold">${formatPrice(item.price)}</p>
                    </div>
                    <button class="remove-from-cart text-red-500 hover:text-red-700 ml-2" data-id="${item.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        });

        cartItems.innerHTML = itemsHTML;
        cartTotal.textContent = formatPrice(total);

        // Add event listeners for remove buttons
        document.querySelectorAll('.remove-from-cart').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                removeFromCart(itemId);
                updateCartPopup();
                updateCartCount();
            });
        });
    }

    // Format price
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);
    }

    // Remove item from cart
    function removeFromCart(itemId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.id !== itemId);
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Update cart count in header
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        document.querySelector('.cart-count').textContent = cart.length;
    }

    // Initialize cart count
    updateCartCount();

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const courseCard = this.closest('.course-card');
            const courseName = courseCard.querySelector('h3').textContent;
            const coursePrice = parseInt(courseCard.querySelector(
                    '.text-indigo-600')
                .textContent
                .replace(/\D/g, ''));
            const courseId = courseName.toLowerCase().replace(/\s+/g, '-');

            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Check if item already in cart
            if (!cart.find(item => item.id === courseId)) {
                cart.push({
                    id: courseId,
                    name: courseName,
                    price: coursePrice
                });

                localStorage.setItem('cart', JSON.stringify(cart));

                // Update cart count
                updateCartCount();

                // Show success message
                const originalText = this.textContent;
                this.textContent = 'Đã thêm!';
                this.classList.remove('bg-indigo-600');
                this.classList.add('bg-green-600');

                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-green-600');
                    this.classList.add('bg-indigo-600');
                }, 1500);
            }
        });
    });
    // document.querySelector('#cart-popup button.bg-indigo-600').addEventListener('click', function() {
    //     const cart = JSON.parse(localStorage.getItem('cart')) || [];
    //     if (cart.length === 0) {
    //         alert('Giỏ hàng của bạn đang trống!');
    //         return;
    //     }

    //     // Calculate total
    //     const total = cart.reduce((sum, item) => sum + item.price, 0);

    //     // Update payment info
    //     document.getElementById('payment-amount').textContent = formatPrice(total);
    //     document.getElementById('payment-content').textContent =
    //         `KHOAHOC${Date.now().toString().slice(-6)}`;

    //     // Close cart and open payment
    //     document.getElementById('cart-popup').classList.add('hidden');
    //     paymentPopup.classList.remove('hidden');
    // });

    // Close payment popup
    closePayment.addEventListener('click', function() {
        paymentPopup.classList.add('hidden');
    });

    cancelPayment.addEventListener('click', function() {
        paymentPopup.classList.add('hidden');
    });

    // Confirm payment
    confirmPayment.addEventListener('click', function() {
        // In real app, you would verify payment here
        paymentPopup.classList.add('hidden');
        successPopup.classList.remove('hidden');

        // Clear cart after successful payment
        localStorage.removeItem('cart');
        updateCartCount();
        updateCartPopup();
    });

    // Close success popup
    closeSuccess.addEventListener('click', function() {
        successPopup.classList.add('hidden');
    });

    // Close popups when clicking outside
    paymentPopup.addEventListener('click', function(e) {
        if (e.target === paymentPopup) {
            paymentPopup.classList.add('hidden');
        }
    });

    successPopup.addEventListener('click', function(e) {
        if (e.target === successPopup) {
            successPopup.classList.add('hidden');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // ===== MOBILE MENU FUNCTIONALITY =====
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const closeMobileMenu = document.getElementById('close-mobile-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileBackdrop = document.getElementById('mobile-backdrop');

    // Toggle mobile menu
    function toggleMobileMenu() {
        mobileMenu.classList.toggle('open');
        mobileBackdrop.classList.toggle('open');
        // Prevent body scroll when menu is open
        document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
    }

    // Event listeners for mobile menu
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', toggleMobileMenu);
    }

    if (closeMobileMenu) {
        closeMobileMenu.addEventListener('click', toggleMobileMenu);
    }

    if (mobileBackdrop) {
        mobileBackdrop.addEventListener('click', toggleMobileMenu);
    }

    // Close mobile menu when clicking on links
    const mobileLinks = document.querySelectorAll('#mobile-menu a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', toggleMobileMenu);
    });

    // ===== USER DROPDOWN FUNCTIONALITY =====
    const userButton = document.getElementById('user-button');
    const userDropdown = document.getElementById('user-dropdown');

    // Toggle user dropdown
    function toggleUserDropdown() {
        userDropdown.classList.toggle('open');
    }

    // Event listener for user dropdown
    if (userButton) {
        userButton.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleUserDropdown();
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (userDropdown && userDropdown.classList.contains('open')) {
            if (!e.target.closest('#user-section')) {
                userDropdown.classList.remove('open');
            }
        }
    });

    // Close dropdown when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && userDropdown && userDropdown.classList.contains('open')) {
            userDropdown.classList.remove('open');
        }
    });

});