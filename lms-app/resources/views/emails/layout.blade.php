<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Xác nhận email - LMS System</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">

    <!-- Main Container -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <!-- Email Container -->
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <table width="100%">
                                <tr>
                                    <td align="center">
                                        <h1
                                            style="color: #ffffff; font-size: 32px; font-weight: bold; margin: 0 0 10px 0;">
                                            <span style="color: #ffffff;">LMS</span>
                                        </h1>
                                        <p style="color: #e2e8f0; font-size: 18px; margin: 0; font-weight: 500;">
                                            Hệ thống học tập trực tuyến
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    @yield('content')

                    <!-- Support Section -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px; border-top: 1px solid #e2e8f0;">
                            <table width="100%">
                                <tr>
                                    <td align="center">
                                        <p style="color: #6b7280; font-size: 14px; margin: 0 0 15px 0;">
                                            Cần hỗ trợ? Chúng tôi luôn sẵn sàng giúp đỡ!
                                        </p>
                                        <a href="mailto:support@lms.edu.vn"
                                            style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                                            support@lms.edu.vn
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1f2937; padding: 20px 30px; text-align: center;">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <p style="color: #9ca3af; font-size: 12px; margin: 0 0 10px 0;">
                                            &copy; 2024 LMS System. All rights reserved.
                                        </p>
                                        <p style="color: #6b7280; font-size: 11px; margin: 0; line-height: 1.4;">
                                            Đây là email tự động, vui lòng không trả lời email này.<br>
                                            Để đảm bảo nhận được email từ chúng tôi, hãy thêm địa chỉ này vào danh sách
                                            liên hệ của bạn.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
