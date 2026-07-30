@extends('emails.layout')

@section('content')
<!-- Content -->
<tr>
     <td style="padding: 36px 30px;">
          <!-- Security Icon -->
          <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                    <td style="text-align: center; padding-bottom: 24px;">
                         <div style="background-color: #fff7ed; width: 72px; height: 72px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; border: 2px solid #ffedd5;">
                              <span style="color: #E8927A; font-size: 32px; font-weight: bold; line-height: 1;">🔒</span>
                         </div>
                         <h2 style="color: #0f172a; font-size: 22px; font-weight: 700; margin: 0 0 8px 0;">
                              Yêu cầu đặt lại mật khẩu
                         </h2>
                         <p style="color: #64748b; font-size: 15px; line-height: 1.5; margin: 0;">
                              Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản <strong>Tiếng Trung XiaoMu</strong>.
                         </p>
                    </td>
               </tr>
          </table>

          <!-- Instruction -->
          <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                    <td style="padding-bottom: 24px;">
                         <p style="color: #334155; font-size: 15px; line-height: 1.6; margin: 0 0 12px 0;">
                              Xin chào <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,
                         </p>
                         <p style="color: #334155; font-size: 15px; line-height: 1.6; margin: 0;">
                              Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào nút bên dưới để tạo mật khẩu mới:
                         </p>
                    </td>
               </tr>
          </table>

          <!-- Button -->
          <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                    <td align="center" style="padding-bottom: 28px;">
                         <a href="{{ $url }}"
                              style="display: inline-block; background: linear-gradient(135deg, #E8927A 0%, #D9775F 100%); color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 12px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(232, 146, 122, 0.35);">
                              Đặt lại mật khẩu
                         </a>
                    </td>
               </tr>
          </table>

          <!-- Alternative Link -->
          <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                    <td style="padding-bottom: 24px;">
                         <p style="color: #64748b; font-size: 13px; line-height: 1.5; margin: 0; text-align: center;">
                              Hoặc sao chép và dán liên kết sau vào trình duyệt của bạn:
                         </p>
                         <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; margin: 12px 0; word-break: break-all;">
                              <a href="{{ $url }}" style="color: #D9775F; font-size: 12px; margin: 0; font-family: monospace; text-decoration: none;">
                                   {{ $url }}
                              </a>
                         </div>
                    </td>
               </tr>
          </table>

          <!-- Security Warning -->
          <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                    <td style="padding-bottom: 20px;">
                         <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 14px 16px; border-radius: 8px;">
                              <p style="color: #b45309; font-size: 13px; line-height: 1.5; margin: 0;">
                                   <strong>Quan trọng:</strong> Liên kết đặt lại mật khẩu sẽ hết hạn sau 60 phút. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
                              </p>
                         </div>
                    </td>
               </tr>
          </table>

          <!-- Security Tips -->
          <table width="100%" cellpadding="0" cellspacing="0">
               <tr>
                    <td>
                         <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 16px; border-radius: 10px;">
                              <h3 style="color: #166534; font-size: 14px; font-weight: 700; margin: 0 0 8px 0;">
                                   💡 Mẹo bảo mật:
                              </h3>
                              <ul style="color: #15803d; font-size: 13px; line-height: 1.6; margin: 0; padding-left: 18px;">
                                   <li>Sử dụng mật khẩu mạnh với ít nhất 8 ký tự</li>
                                   <li>Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                                   <li>Không sử dụng lại mật khẩu cũ</li>
                                   <li>Không chia sẻ mật khẩu với bất kỳ ai</li>
                              </ul>
                         </div>
                    </td>
               </tr>
          </table>
     </td>
</tr>
@endsection