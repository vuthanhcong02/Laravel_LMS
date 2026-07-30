<!DOCTYPE html>
<html>

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>{{ config('app.name', 'XiaoMu') }}</title>
</head>

<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #334155;">

     <!-- Main Container -->
     <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc;">
          <tr>
               <td align="center" style="padding: 30px 15px;">
                    <!-- Email Card -->
                    <table width="100%" cellpadding="0" cellspacing="0"
                         style="max-width: 600px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01); overflow: hidden; border: 1px solid #f1f5f9;">

                         <!-- Header Banner -->
                         <tr>
                              <td style="background: linear-gradient(135deg, #E8927A 0%, #D9775F 100%); color: #ffffff; padding: 32px 24px; text-align: center;">
                                   <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                             <td align="center">
                                                  <div style="display: inline-block; vertical-align: middle; margin-bottom: 8px;">
                                                       <img src="{{ asset('logo.png') }}" alt="XiaoMu Logo" width="48" height="48" style="border-radius: 50%; border: 2px solid rgba(255,255,255,0.8); display: block; margin: 0 auto;">
                                                  </div>
                                                  <h1 style="color: #ffffff; font-size: 26px; font-weight: 800; margin: 6px 0 4px 0; letter-spacing: -0.5px;">
                                                       Tiếng Trung XiaoMu
                                                  </h1>
                                                  <p style="color: rgba(255, 255, 255, 0.9); font-size: 15px; margin: 0; font-weight: 500;">
                                                       Học Tiếng Trung hiệu quả & bài bản cùng Tiếng Trung XiaoMu
                                                  </p>
                                             </td>
                                        </tr>
                                   </table>
                              </td>
                         </tr>

                         <!-- Body Content -->
                         @yield('content')

                         <!-- Support Section -->
                         @php
                              $supportEmail = config('mail.support_address', 'xiaomuhsk@gmail.com');
                              $brandName = (config('app.name') && config('app.name') !== 'Laravel') ? config('app.name') : 'Tiếng Trung XiaoMu';
                         @endphp
                         <tr>
                              <td style="background-color: #f8fafc; padding: 24px 30px; border-top: 1px solid #f1f5f9; text-align: center;">
                                   <p style="color: #64748b; font-size: 14px; margin: 0 0 8px 0; font-weight: 500;">
                                        Cần hỗ trợ? Chúng tôi luôn sẵn sàng giúp đỡ bạn!
                                   </p>
                                   <a href="mailto:{{ $supportEmail }}"
                                        style="color: #E8927A; text-decoration: none; font-weight: 600; font-size: 14px;">
                                        {{ $supportEmail }}
                                   </a>
                              </td>
                         </tr>

                         <!-- Footer -->
                         <tr>
                              <td style="background-color: #0f172a; padding: 24px 30px; text-align: center;">
                                   <p style="color: #94a3b8; font-size: 13px; margin: 0 0 8px 0;">
                                        &copy; {{ date('Y') }} {{ $brandName }}. All rights reserved.
                                   </p>
                                   <p style="color: #64748b; font-size: 11px; margin: 0; line-height: 1.5;">
                                        Đây là email tự động từ hệ thống, vui lòng không trả lời email này.<br>
                                        Để đảm bảo luôn nhận được thông báo, hãy thêm địa chỉ này vào danh sách người gửi tin cậy.
                                   </p>
                              </td>
                         </tr>

                    </table>
               </td>
          </tr>
     </table>

</body>

</html>