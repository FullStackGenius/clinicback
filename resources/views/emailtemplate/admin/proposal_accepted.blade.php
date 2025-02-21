<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal Accepted by Freelancer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
            margin-top: 20px;
        }
        .footer {
            font-size: 14px;
            margin-top: 20px;
            text-align: center;
            color: #888;
        }
        .button {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            Freelancer Proposal Accepted!
        </div>
        
        <div class="content">
            <p>The proposal for the job titled "<strong>{{ ucfirst(@$project->title) }}</strong>" has been accepted by the freelancer <strong>{{ ucfirst(@$proposal->freelancerUser->name) }}  {{ @$proposal->freelancerUser->last_name }}</strong>.</p>
            <p>The client <strong>{{ ucfirst(@$project->clientUser->name) }} {{ @$project->clientUser->last_name }}</strong> has selected this freelancer to start working on the project.</p>
            <p>Freelancer's Email: <a href="mailto:{{ @$proposal->freelancerUser->email }}">{{ @$proposal->freelancerUser->email }}</a></p>
            <p>Click the button below to view the project details:</p>
            <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH }}" class="button">View Project Details</a>
        </div>
        
        <div class="footer">
            <p>Thank you for managing the platform's projects!</p>
        </div>
    </div>

</body>
</html>
