<!-- Cart Popup -->
<div id="cart-popup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
     <div class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-xl">
          <div class="flex flex-col h-full">
               <!-- Header -->
               <div class="flex justify-between items-center p-4 border-b">
                    <h2 class="text-xl font-bold">Giỏ hàng của bạn</h2>
                    <button id="close-cart" class="text-gray-500 hover:text-gray-700">
                         <i class="fas fa-times text-xl"></i>
                    </button>
               </div>

               <!-- Cart Items -->
               <div class="flex-1 overflow-y-auto p-4">
                    <div id="cart-items">
                         <!-- Cart items will be loaded here -->
                         <div class="text-center py-8 text-gray-500">
                              <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                              <p>Giỏ hàng của bạn đang trống</p>
                         </div>
                    </div>
               </div>

               <!-- Footer -->
               <div class="border-t p-4">
                    <div class="flex justify-between items-center mb-4">
                         <span class="text-lg font-semibold">Tổng cộng:</span>
                         <span id="cart-total" class="text-lg font-bold text-indigo-600">0đ</span>
                    </div>
                    <button
                         class="block w-full text-center btn-primary text-white p-4 rounded-md transition duration-300 font-semibold">
                         Thanh toán
                    </button>
               </div>
          </div>
     </div>
</div>