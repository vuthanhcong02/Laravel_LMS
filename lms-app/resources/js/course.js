 document.addEventListener('DOMContentLoaded', function() {
     // Lấy số lượng sản phẩm trong giỏ hàng từ localStorage
     let cartCount = localStorage.getItem('cartCount') || 0;
     document.querySelector('.cart-count').textContent = cartCount;

     // Xử lý bộ lọc
     const filterButtons = document.querySelectorAll('.filter-btn');
     filterButtons.forEach(button => {
         button.addEventListener('click', function() {
             filterButtons.forEach(btn => btn.classList.remove('active-filter'));
             this.classList.add('active-filter');
         });
     });
 });
 document.addEventListener('DOMContentLoaded', function() {
     // Lấy số lượng sản phẩm trong giỏ hàng từ localStorage
     let cartCount = localStorage.getItem('cartCount') || 0;
     document.querySelector('.cart-count').textContent = cartCount;

     // Thêm vào giỏ hàng
     const addToCartBtn = document.querySelector('.add-to-cart');
     if (addToCartBtn) {
         addToCartBtn.addEventListener('click', function() {
             cartCount = parseInt(cartCount) + 1;
             localStorage.setItem('cartCount', cartCount);
             document.querySelector('.cart-count').textContent = cartCount;

             // Hiệu ứng thông báo
             const originalText = addToCartBtn.textContent;
             addToCartBtn.textContent = 'Đã thêm vào giỏ!';
             addToCartBtn.classList.remove('bg-indigo-600');
             addToCartBtn.classList.add('bg-green-600');

             setTimeout(() => {
                 addToCartBtn.textContent = originalText;
                 addToCartBtn.classList.remove('bg-green-600');
                 addToCartBtn.classList.add('bg-indigo-600');
             }, 2000);
         });
     }

     // Accordion functionality
     const accordions = document.querySelectorAll('.accordion');
     accordions.forEach(accordion => {
         const header = accordion.querySelector('.accordion-header');
         header.addEventListener('click', () => {
             accordion.classList.toggle('active');
         });
     });
 });