<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Message Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #fff;
            background: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .message-box {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>New Message Notification</h2>
        
        <p>Hello {{ $recevier['name'] ?? 'User' }},</p>
        
        <p>You have received a new message from <strong>{{ $senderName ?? 'Unknown' }}</strong>.</p>
        
        @if(!empty($messageData['message']))
        <div class="message-box">
            <p><strong>Message:</strong></p>
            <p>{{ $messageData['message'] }}</p>
        </div>
        @endif
        {{-- <p><strong>Message:</strong> No message content provided.</p>
        @endif --}}

        @if(!empty($messageData['file_path']))
        <p><strong>Attachment:</strong> <a href="{{ $messageData['full_file_path'] }}" target="_blank">View File</a></p>
        @endif
        
        <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH }}/chat" class="button">View Conversation</a>
        
        <p>Thank you for using our platform!</p>
        <p>Best regards,<br>{{ config('app.name') }} Team</p>
    </div>
</body>
</html>
