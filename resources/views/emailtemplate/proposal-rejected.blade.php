<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: #dc3545;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }
        .email-body p {
            margin: 15px 0;
        }
        .email-footer {
            background: #f8f9fa;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #555555;
        }
        .cta-button {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        .cta-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="email-container">
    <div class="email-header">
        Proposal Rejected
    </div>

    <div class="email-body">
        <p>Dear <strong>{{ $freelancerName }}</strong>,</p>

        <p>We appreciate your interest in <strong>{{ $jobTitle }}</strong>. However, after careful review, we regret to inform you that your proposal has not been selected at this time.</p>

        {{-- <p><strong>Reason:</strong> {{ $reason }}</p> --}}

        <p>We truly value your skills and encourage you to explore other opportunities on our platform.</p>

        <p style="text-align: center;">
            <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH }}/projects" class="cta-button">Browse More Projects</a>
        </p>
    </div>

    <div class="email-footer">
        Best regards, <br>
        <strong>{{ config('app.name') }}</strong>
    </div>
</div>

</body>
</html>
