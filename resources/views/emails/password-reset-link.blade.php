<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} – Reset Password</title>
    <style>
        body {
            margin: 0;
            padding: 40px 20px;
            font-family: Arial, sans-serif;
            background-color: #f4f4f6;
            color: #263238;
        }

        .wrapper {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(15, 50, 86, 0.08);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #015b9c 0%, #0a7cd1 100%);
            padding: 32px 40px;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
        }

        .content {
            padding: 32px 40px;
        }

        .content p {
            margin: 0 0 18px;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            margin: 24px 0;
            background-color: #f8b93a;
            color: #212121 !important;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
        }

        .muted {
            color: #607d8b;
            font-size: 14px;
        }

        .footer {
            padding: 24px 40px 32px;
            border-top: 1px solid #e6ecf1;
            background-color: #fafbfd;
            font-size: 13px;
            color: #78909c;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Reset your password</h1>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $user->name ?? $user->email }}</strong>,</p>

            <p>We received a request to reset the password for your {{ $appName }} account. Click the button below to choose a new password.</p>

            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </p>

            <p class="muted">This password reset link will expire in {{ $expiryMinutes }} minutes. If the button above doesn’t work, copy and paste the link below into your browser:</p>

            <p class="muted" style="word-break: break-word;">{{ $resetUrl }}</p>

            <p>If you didn’t request a password reset, you can safely ignore this email.</p>
        </div>

        <div class="footer">
            <p>Need help? Reply to this email or contact us at {{ $supportEmail }}.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

