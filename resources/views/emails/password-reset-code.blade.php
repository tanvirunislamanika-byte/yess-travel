<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 32px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }
        .header h1 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }
        .content {
            margin-top: 28px;
            line-height: 1.6;
            color: #555;
        }
        .code-box {
            background-color: #f8f9fa;
            border: 2px dashed #007bff;
            border-radius: 10px;
            text-align: center;
            padding: 30px;
            margin: 30px 0;
        }
        .code {
            font-size: 42px;
            letter-spacing: 10px;
            font-weight: bold;
            color: #007bff;
            font-family: 'Courier New', monospace;
        }
        .important {
            background-color: #fff3cd;
            border: 1px solid #ffe08a;
            padding: 16px;
            border-radius: 6px;
            margin: 24px 0;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            color: #666;
            font-size: 14px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }} Password Reset</h1>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $user->name }}</strong>,</p>

            <p>We received a request to reset the password for your {{ config('app.name') }} account. Use the verification code below to continue in the mobile app.</p>

            <div class="code-box">
                <div class="code">{{ $otp }}</div>
            </div>

            <div class="important">
                This code will expire in {{ $expiryMinutes ?? 15 }} minutes. If it expires, please request a new password reset code from the app.
            </div>

            <p>If you did not request a password reset, you can safely ignore this email.</p>
        </div>

        <div class="footer">
            <p>Warm regards,<br>
            The {{ config('app.name') }} Team<br>
            <a href="{{ url('/') }}">{{ url('/') }}</a></p>
        </div>
    </div>
</body>
</html>

