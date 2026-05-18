<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            padding: 32px;
        }
        .header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 3px solid #1e87f0;
        }
        .header h1 {
            margin: 0;
            color: #1e87f0;
            font-size: 24px;
        }
        .content {
            margin-top: 24px;
            line-height: 1.6;
        }
        .otp-box {
            background-color: #f8fbff;
            border: 2px dashed #1e87f0;
            border-radius: 12px;
            text-align: center;
            padding: 28px 16px;
            margin: 32px 0;
        }
        .otp-code {
            font-size: 44px;
            letter-spacing: 10px;
            font-weight: bold;
            color: #1e87f0;
        }
        .footer {
            margin-top: 32px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Password Reset</h1>
    </div>

    <div class="content">
        <p>Hi <strong>{{ $user->name ?? 'there' }}</strong>,</p>
        <p>
            We received a request to reset the password for your {{ config('app.name') }} account.
            Use the verification code below within the app to continue.
        </p>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p>
            This code will expire in {{ $expiryMinutes ?? 15 }} minutes. If it expires, please request a new
            password reset code from the mobile app.
        </p>
        <p>If you did not request a password reset, you can safely ignore this email.</p>
    </div>

    <div class="footer">
        <p>Warm regards,<br>{{ config('app.name') }} Team</p>
        <p><a href="{{ url('/') }}">{{ url('/') }}</a></p>
    </div>
</div>
</body>
</html> 