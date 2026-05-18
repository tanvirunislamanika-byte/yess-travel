<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }
        .verification-code {
            text-align: center;
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin: 30px 0;
            border: 2px dashed #007bff;
        }
        .code {
            font-size: 48px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .instructions {
            background-color: #e7f3ff;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/'.widget(1)->extra_image_1) }}" alt="Logo" style="max-width: 200px; margin-bottom: 20px;">
            <h1>Email Verification</h1>
        </div>

        <div class="greeting">
            Hello <strong>{{ $user->name }}</strong>!
        </div>

        <p>Thank you for registering with us. To complete your registration, please use the verification code below:</p>

        <div class="verification-code">
            <div class="code">{{ $verificationCode }}</div>
        </div>

        <div class="instructions">
            <h3>How to verify your email:</h3>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Copy the 6-digit code above</li>
                <li>Go to the verification page</li>
                <li>Enter the code in the verification field</li>
                <li>Click "Verify Email" to complete your registration</li>
            </ol>
        </div>

        <div class="warning">
            <strong>Important:</strong> This verification code will expire in 15 minutes for security reasons. If you don't verify within this time, you'll need to request a new code.
        </div>

        <p>If you didn't create an account with us, please ignore this email.</p>

        <div class="footer">
            <p style="color: #000; margin-top: 40px; font-weight: bold;">
                If you have any questions, feel free to contact us.
                <br>
                We look forward to having you as a member!
            </p>
            <p style="color: #555; margin-top: 40px;">
                Warm Regards,<br>
                The {{ config('app.name') }} Team<br>
                <a href="{{url('/')}}" target="_blank" style="color: #015b9c;">{{url('/')}}</a>
            </p>
        </div>
    </div>
</body>
</html>
