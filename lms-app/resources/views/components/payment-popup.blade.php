<!-- Payment Popup -->
<div id="payment-popup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
     <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
          <!-- Header -->
          <div class="flex justify-between items-center p-6 border-b">
               <h2 class="text-xl font-bold text-gray-800">Thanh toán</h2>
               <button id="close-payment" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
               </button>
          </div>

          <!-- Content -->
          <div class="p-6">
               <!-- Thông tin thanh toán -->
               <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                         <span class="text-gray-600">Số tiền:</span>
                         <span id="payment-amount" class="text-2xl font-bold text-indigo-600">0đ</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                         <span class="text-gray-600">Phương thức:</span>
                         <span class="font-semibold">Chuyển khoản QR</span>
                    </div>
               </div>

               <!-- Mã QR -->
               <div class="text-center mb-6">
                    <div class="bg-white p-4 rounded-lg border-2 border-dashed border-gray-300 inline-block mb-4">
                         <!-- Placeholder for QR code - in real app, generate dynamic QR -->
                         <div
                              class="w-64 h-64 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                              <div class="text-center">
                                   <i class="fas fa-qrcode text-4xl text-indigo-600 mb-2"></i>
                                   <p class="text-sm text-gray-600">Mã QR thanh toán</p>
                              </div>
                         </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Quét mã QR để thanh toán</p>
               </div>

               <!-- Thông tin tài khoản -->
               <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold mb-3 text-gray-800">Thông tin chuyển khoản</h3>
                    <div class="space-y-2 text-sm">
                         <div class="flex justify-between">
                              <span class="text-gray-600">Ngân hàng:</span>
                              <span class="font-medium">MB Bank</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Số tài khoản:</span>
                              <span class="font-medium">0987654321</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Chủ tài khoản:</span>
                              <span class="font-medium">NGUYEN VAN A</span>
                         </div>
                         <div class="flex justify-between">
                              <span class="text-gray-600">Nội dung:</span>
                              <span id="payment-content" class="font-medium text-indigo-600">KHOAHOC123</span>
                         </div>
                    </div>
               </div>

               <!-- Nút hành động -->
               <div class="flex space-x-3">
                    <button id="confirm-payment"
                         class="flex-1 bg-green-600 text-white py-3 rounded-md hover:bg-green-700 transition duration-300 font-semibold flex items-center justify-center">
                         <i class="fas fa-check-circle mr-2"></i>
                         Đã chuyển khoản
                    </button>
                    <button id="cancel-payment"
                         class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-md hover:bg-gray-50 transition duration-300 font-semibold">
                         Hủy
                    </button>
               </div>
          </div>
     </div>
</div>