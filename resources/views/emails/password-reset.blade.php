<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dat lai mat khau</title>
    <style>
        :root {
            color-scheme: light;
        }

        body {
            margin: 0;
            padding: 0;
            background: #eef2f5;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #0f172a;
        }

        .wrapper {
            width: 100%;
            padding: 28px 12px;
        }

        .container {
            max-width: 620px;
            margin: 0 auto;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #d7e0e8;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        .header {
            padding: 24px;
            text-align: center;
            color: #ffffff;
            background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .content {
            padding: 28px 30px;
            font-size: 15px;
            color: #334155;
        }

        .content p {
            margin: 0 0 14px;
        }

        .cta-wrap {
            text-align: center;
            margin: 24px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 12px;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            letter-spacing: 0.02em;
            background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
        }

        .meta {
            margin-top: 18px;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #bae6fd;
            background: #f0f9ff;
            color: #0c4a6e;
            font-size: 13px;
        }

        .footer {
            margin-top: 16px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
            </div>

            <div class="content">
                <p>Xin chao {{ $user->name }},</p>
                <p>Chung toi nhan duoc yeu cau dat lai mat khau cho tai khoan cua ban.</p>
                <p>Nhan vao nut ben duoi de dat lai mat khau:</p>

                <div class="cta-wrap">
                    <a href="{{ $resetUrl }}" class="button">Dat lai mat khau</a>
                </div>

                <p>Link nay se het han sau 60 phut. Neu ban khong yeu cau dat lai mat khau, vui long bo qua email nay.</p>
                <p>Tran trong,<br>{{ config('app.name') }}</p>

                <div class="meta">
                    Neu nut khong hoat dong, sao chep va mo link sau trong trinh duyet:
                </div>
            </div>
        </div>

        <div class="footer">
            {{ $resetUrl }}
        </div>
    </div>
</body>
</html>
