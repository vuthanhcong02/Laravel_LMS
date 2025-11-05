<!DOCTYPE html>
<html lang="vi">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description"
          content="Khóa học giá rẻ, chất lượng cao. Học mọi lúc, mọi nơi với hàng ngàn khóa học đa dạng.">
     <meta name="keywords" content="khóa học, học online, giá rẻ, kỹ năng, kiến thức">
     <title>Khóa Học Giá Rẻ</title>
     <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
     <style>
     @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

     body {
          font-family: 'Inter', sans-serif;
     }

     .course-card {
          transition: all 0.3s ease;
          overflow: hidden;
     }

     .course-card:hover {
          transform: translateY(-8px);
          box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
     }

     .course-image {
          transition: transform 0.5s ease;
     }

     .course-card:hover .course-image {
          transform: scale(1.05);
     }

     .price-tag {
          position: absolute;
          top: 12px;
          right: 12px;
          background: rgba(239, 68, 68, 0.9);
          color: white;
          padding: 4px 10px;
          border-radius: 20px;
          font-weight: 600;
          font-size: 0.875rem;
     }

     .cart-count {
          position: absolute;
          top: -8px;
          right: -8px;
          background-color: #ef4444;
          color: white;
          border-radius: 50%;
          width: 20px;
          height: 20px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 12px;
     }

     .hero-bg {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
     }

     .floating {
          animation: floating 3s ease-in-out infinite;
     }

     @keyframes floating {
          0% {
               transform: translate(0, 0px);
          }

          50% {
               transform: translate(0, 15px);
          }

          100% {
               transform: translate(0, -0px);
          }
     }

     .pulse {
          animation: pulse 2s infinite;
     }

     @keyframes pulse {
          0% {
               transform: scale(1);
          }

          50% {
               transform: scale(1.05);
          }

          100% {
               transform: scale(1);
          }
     }

     .category-card {
          transition: all 0.3s ease;
     }

     .category-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
     }

     .btn-primary {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          transition: all 0.3s ease;
     }

     .btn-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 10px 15px -3px rgba(102, 126, 234, 0.4);
     }

     .loading-bar {
          height: 4px;
          width: 100%;
          background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
          background-size: 200% 100%;
          animation: loading 2s infinite linear;
     }

     @keyframes loading {
          0% {
               background-position: 200% 0;
          }

          100% {
               background-position: -200% 0;
          }
     }
     </style>
</head>

<body class="bg-gray-50">
     {{-- <div class="loading-bar"></div> --}}

     <!-- Header -->
     <x-header />

     <!-- Main Content -->
     <main>
          @yield('content')
     </main>

     <!-- Footer -->
     <x-footer />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
     <script>
     // Khởi tạo AOS (Animate On Scroll)
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
          document.querySelector('#cart-popup button.bg-indigo-600').addEventListener('click', function() {
               const cart = JSON.parse(localStorage.getItem('cart')) || [];
               if (cart.length === 0) {
                    alert('Giỏ hàng của bạn đang trống!');
                    return;
               }

               // Calculate total
               const total = cart.reduce((sum, item) => sum + item.price, 0);

               // Update payment info
               document.getElementById('payment-amount').textContent = formatPrice(total);
               document.getElementById('payment-content').textContent =
                    `KHOAHOC${Date.now().toString().slice(-6)}`;

               // Close cart and open payment
               document.getElementById('cart-popup').classList.add('hidden');
               paymentPopup.classList.remove('hidden');
          });

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
     </script>
</body>

</html>