<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Milestone Completion Request</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f6f6f6;
      padding: 20px;
      color: #333;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .header {
      text-align: center;
      padding-bottom: 20px;
    }
    .header h2 {
      color: #4CAF50;
      margin: 0;
    }
    .content {
      font-size: 16px;
      line-height: 1.6;
    }
    .button {
      display: inline-block;
      padding: 12px 24px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 20px;
    }
    .footer {
      text-align: center;
      font-size: 12px;
      color: #888;
      margin-top: 30px;
    }
    .details {
      margin-top: 20px;
      background: #f9f9f9;
      padding: 15px;
      border-radius: 6px;
    }
    .details p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Milestone Completion Request</h2>
    </div>
    <div class="content">
      <p>Hello {{ $milestone->contract?->client?->name ?? 'Valued Client' }} {{ $milestone->contract?->client?->last_name ?? '' }},</p>

      <p>
        I'm pleased to inform you that the following milestone has been successfully completed for your project.
      </p>

      <div class="details">
        <p><strong>📌 Project Name:</strong> {{  $milestone?->contract?->project?->title ?? 'N/A' }}</p>
        <p><strong>🎯 Milestone Title:</strong> {{ $milestone?->title ?? 'N/A' }}</p>
        <p><strong>📝 Description:</strong> {{ $milestone?->description ?? 'N/A' }}</p>
        <p><strong>💵 Amount:</strong> {{ isset($milestone?->amount) ? '$' . number_format($milestone?->amount, 2) : 'N/A' }}</p>
      </div>

      <p>
        Kindly review the work and if everything looks good, approve this milestone to proceed to the next step.
      </p>

      @if(!empty($milestone?->contract?->id))
        <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH."/client/contracts-details/".$milestone?->contract?->id }}" class="button">Review & Approve</a>
      @else
        <p><em>The approval link is currently unavailable.</em></p>
      @endif

      <p>If you have any questions or feedback, feel free to reach out. I'm always happy to assist.</p>

      <p>Thank you for your collaboration!</p>

      <p>Best regards,<br>{{ $milestone?->contract?->freelancer?->name ?? 'Your Freelancer' }} {{ $milestone->contract?->freelancer?->last_name ?? '' }}</p>
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
  </div>
</body>
</html>
