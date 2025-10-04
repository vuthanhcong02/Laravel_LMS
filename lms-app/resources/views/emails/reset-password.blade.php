@extends('emails.layout')

@section('content')
    <!-- Content -->
    <tr>
        <td style="padding: 40px 30px;">
            <!-- Security Icon -->
            <table width="100%">
                <tr>
                    <td style="text-align: center; padding-bottom: 30px;">
                        <div
                            style="background-color: #dbeafe; width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <span style="color: #3b82f6; font-size: 32px;">🔒</span>
                        </div>
                        <h2 style="color: #1f2937; font-size: 24px; font-weight: bold; margin: 0 0 10px 0;">
                            Yêu cầu đặt lại mật khẩu
                        </h2>
                        <p style="color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0;">
                            Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản LMS System
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Instruction -->
            <table width="100%">
                <tr>
                    <td style="padding-bottom: 30px;">
                        <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
                            Xin chào <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,
                        </p>
                        <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                            Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào nút bên
                            dưới để tạo mật khẩu mới:
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Button -->
            <table width="100%">
                <tr>
                    <td align="center" style="padding-bottom: 30px;">
                        <a href="{{ $url }}"
                            style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); 
                                                  color: #ffffff; 
                                                  padding: 14px 32px; 
                                                  text-decoration: none; 
                                                  border-radius: 8px; 
                                                  font-size: 16px; 
                                                  font-weight: 600; 
                                                  display: inline-block; 
                                                  text-align: center;
                                                  box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
                                                  transition: all 0.3s ease;">
                            Đặt lại mật khẩu
                        </a>
                    </td>
                </tr>
            </table>

            <!-- Alternative Link -->
            <table width="100%">
                <tr>
                    <td style="padding-bottom: 20px;">
                        <p style="color: #6b7280; font-size: 14px; line-height: 1.5; margin: 0; text-align: center;">
                            Hoặc sao chép và dán liên kết sau vào trình duyệt của bạn:
                        </p>
                        <div
                            style="background-color: #f8fafc; 
                                                   border: 1px solid #e2e8f0; 
                                                   border-radius: 6px; 
                                                   padding: 12px; 
                                                   margin: 15px 0;
                                                   word-break: break-all;">
                            <p style="color: #3b82f6; font-size: 12px; margin: 0; font-family: monospace;">
                                {{ $url }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Security Warning -->
            <table width="100%">
                <tr>
                    <td>
                        <div
                            style="background-color: #fef3cd; 
                                                   border-left: 4px solid #f59e0b; 
                                                   padding: 16px; 
                                                   border-radius: 4px;">
                            <p style="color: #92400e; font-size: 14px; line-height: 1.5; margin: 0;">
                                <strong>Quan trọng:</strong> Liên kết đặt lại mật khẩu sẽ hết hạn sau 60 phút.
                                Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này và kiểm tra bảo mật tài
                                khoản của bạn.
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Security Tips -->
            <table width="100%" style="margin-top: 20px;">
                <tr>
                    <td>
                        <div
                            style="background-color: #ecfdf5; 
                                                   border: 1px solid #d1fae5; 
                                                   padding: 16px; 
                                                   border-radius: 6px;">
                            <h3 style="color: #065f46; font-size: 14px; font-weight: 600; margin: 0 0 8px 0;">
                                💡 Mẹo bảo mật:
                            </h3>
                            <ul style="color: #047857; font-size: 13px; line-height: 1.5; margin: 0; padding-left: 16px;">
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
