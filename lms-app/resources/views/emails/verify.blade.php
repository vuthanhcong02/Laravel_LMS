@extends('emails.layout')
@section('content')
    <!-- Content -->
    <tr>
        <td style="padding: 40px 30px;">
            <!-- Welcome Message -->
            <table width="100%">
                <tr>
                    <td style="text-align: center; padding-bottom: 30px;">
                        <div
                            style="background-color: #dbeafe; width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <span style="color: #3b82f6; font-size: 32px;">✓</span>
                        </div>
                        <h2 style="color: #1f2937; font-size: 24px; font-weight: bold; margin: 0 0 10px 0;">
                            Xin chào {{ $user->first_name }} {{ $user->last_name }}!
                        </h2>
                        <p style="color: #6b7280; font-size: 16px; line-height: 1.6; margin: 0;">
                            Cảm ơn bạn đã đăng ký tài khoản tại LMS System
                        </p>
                    </td>
                </tr>
            </table>

            <!-- Instruction -->
            <table width="100%">
                <tr>
                    <td style="padding-bottom: 30px;">
                        <p style="color: #4b5563; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
                            Để hoàn tất đăng ký và bắt đầu hành trình học tập của bạn, vui lòng xác nhận
                            địa chỉ email bằng cách nhấp vào nút bên dưới:
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
                            Xác nhận Email
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

            <!-- Note -->
            <table width="100%">
                <tr>
                    <td>
                        <div
                            style="background-color: #fef3cd; 
                                                   border-left: 4px solid #f59e0b; 
                                                   padding: 16px; 
                                                   border-radius: 4px;">
                            <p style="color: #92400e; font-size: 14px; line-height: 1.5; margin: 0;">
                                <strong>Lưu ý:</strong> Liên kết xác nhận sẽ hết hạn sau 60 phút.
                                Nếu bạn không tạo tài khoản, vui lòng bỏ qua email này.
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
