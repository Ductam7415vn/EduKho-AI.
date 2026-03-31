<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Space Grotesk', sans-serif;
            background-color: #f0f9ff;
            color: #1e293b;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
            padding: 40px;
            margin-top: 20px;
            border: 1px solid rgba(15, 118, 110, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        h1 {
            color: #0f172a;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        .content {
            color: #475569;
            margin-bottom: 24px;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
            color: #ffffff !important;
            padding: 14px 36px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            text-align: center;
            margin: 32px 0;
            box-shadow: 0 10px 26px rgba(15, 76, 129, 0.22);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 14px;
        }
        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 32px rgba(15, 76, 129, 0.28);
        }
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }
        .warning {
            background: linear-gradient(140deg, #fef3c7 0%, #fef9c3 100%);
            border: 1px solid #fde047;
            border-radius: 12px;
            padding: 16px;
            margin: 24px 0;
            color: #854d0e;
            font-size: 14px;
            font-weight: 500;
        }
        .link-text {
            color: #64748b;
            font-size: 12px;
            word-break: break-all;
            margin-top: 24px;
            padding: 16px;
            background: #f1f5f9;
            border-radius: 8px;
        }
        .benefits {
            margin: 24px 0;
            padding: 24px;
            background: linear-gradient(140deg, #f0fdf4 0%, #ecfeff 100%);
            border-radius: 12px;
            border: 1px solid #5eead4;
        }
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            color: #0f766e;
        }
        .benefit-item:last-child {
            margin-bottom: 0;
        }
        .benefit-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            flex-shrink: 0;
            color: #14b8a6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <a href="{{ url('/') }}" class="logo">
                    <span style="font-size: 32px;">🎒</span> Hệ thống Quản lý Thiết bị
                </a>
            </div>
            
            <h1>Xin chào {{ $name }}! 👋</h1>
            
            <div class="content">
                <p>Chào mừng bạn đến với <strong>Hệ thống Quản lý Thiết bị Dạy học</strong> - nơi kết nối giáo viên với các thiết bị giảng dạy hiện đại.</p>
                
                <p>Để hoàn tất quá trình đăng ký và bắt đầu trải nghiệm, vui lòng xác thực địa chỉ email của bạn:</p>
                
                <div style="text-align: center;">
                    <a href="{{ $verificationUrl }}" class="button">
                        Xác thực Email
                    </a>
                </div>
                
                <div class="warning">
                    <strong>⏰ Lưu ý:</strong> Link xác thực này sẽ hết hạn sau 24 giờ. Nếu link đã hết hạn, vui lòng đăng ký lại.
                </div>
                
                <div class="benefits">
                    <h3 style="margin-top: 0; color: #0f766e;">Sau khi xác thực, bạn có thể:</h3>
                    <div class="benefit-item">
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Mượn trả thiết bị dạy học nhanh chóng</span>
                    </div>
                    <div class="benefit-item">
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Lập kế hoạch giảng dạy thông minh</span>
                    </div>
                    <div class="benefit-item">
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Nhận thông báo về lịch mượn trả</span>
                    </div>
                    <div class="benefit-item">
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Sử dụng AI trợ lý tìm thiết bị phù hợp</span>
                    </div>
                </div>
                
                <p style="color: #64748b; font-size: 14px; margin-top: 24px;">
                    Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này.
                </p>
            </div>
            
            <div class="link-text">
                <p style="margin: 0 0 8px 0;"><strong>Gặp vấn đề với nút xác thực?</strong></p>
                <p style="margin: 0;">Copy link sau và dán vào trình duyệt:</p>
                <p style="margin: 8px 0 0 0; color: #0f766e;">{{ $verificationUrl }}</p>
            </div>
            
            <div class="footer">
                <p>Email này được gửi tự động, vui lòng không reply.</p>
                <p>© {{ date('Y') }} Hệ thống Quản lý Thiết bị Dạy học</p>
            </div>
        </div>
    </div>
</body>
</html>