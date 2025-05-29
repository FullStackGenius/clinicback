<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Milestone Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; padding: 20px; max-width: 600px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { background-color: #28a745; color: white; text-align: center; padding: 10px 0; border-radius: 10px 10px 0 0; }
        .content { padding: 20px; color: #333; }
        .footer { text-align: center; margin-top: 20px; font-size: 14px; color: #777; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Received 🎉</h2>
        </div>
        <div class="content">
           
            <p>Dear {{ @$freelancer['name'] }},</p>
            <p>We are pleased to inform you that a payment for the milestone <strong>"{{ $milestone['title'] }}"</strong> has been successfully processed by your client.</p>
            
            <h3>Payment Details:</h3>
            <ul>
                <li><strong>Milestone:</strong> {{ $milestone['title'] }}</li>
                <li><strong>Amount Received:</strong> ${{ number_format($milestone['amount'], 2) }}</li>
                <li><strong>Transaction ID:</strong> {{ $milestone['transaction_id'] }}</li>
                <li><strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($milestone['paid_at'])->format('d M Y, h:i A') }}</li>
            </ul>

            <p>The payment has been successfully credited to your account.</p>

            <p>If you have any questions or need further assistance, please feel free to reach out.</p>

            <p style="text-align: center;">
                <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH }}" class="btn">Go to Dashboard</a>
            </p>

            <p>Best Regards,</p>
            <p><strong>{{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
