<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    @php $resetLink = $user->resetPasswordLink; @endphp
    <div class="email-container">
        <h2>Hello {{ $user->name }},</h2>
        <p>We received a request to reset your password. Click the button below to set a new password:</p>

        <a href="{{ $resetLink }}" class="button">Reset Password</a>

        <p>If you did not request a password reset, please ignore this email or contact our support team if you have questions.</p>

        <p>Thank you,<br>The {{ config('app.name') }} Team</p>

        <div class="footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
           
        </div>
    </div>
</body>
</html>
