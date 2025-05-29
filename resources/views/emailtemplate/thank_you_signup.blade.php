<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            font-size: 22px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            color: #fff;
            background: #28a745;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            Welcome to {{ config('app.name') }}!
        </div>
        <div class="content">
            <p>Hello {{ $data['name'] ?? 'User' }},</p>
            <p>Thank you for signing up! We are thrilled to have you onboard.</p>
            <p>Click below to explore your Application:</p>
            
            {{-- <a href="{{ $data['dashboard_url'] ?? url('/') }}" class="button">Go to Dashboard</a>
            <br><br> --}}
            <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH }}/sign-in" class="button" style="background: #007bff;">Login Now</a>

            <p>If you have any questions, feel free to contact our support team.</p>
        </div>
        <div class="footer">
            <p>Best regards,<br>{{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>
