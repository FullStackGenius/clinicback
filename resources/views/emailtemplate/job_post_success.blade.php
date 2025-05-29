<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Post Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #ece9e6, #ffffff);
            padding: 40px 20px;
            color: #333;
        }
        .email-container {
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        h3 {
            color: #007bff;
            font-size: 22px;
            margin-top: 0;
        }
        h4 {
            margin-top: 30px;
            font-size: 20px;
            color: #555;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        p {
            font-size: 16px;
            line-height: 1.7;
            color: #555;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        ul li {
            padding: 10px 0;
            border-bottom: 1px dashed #ddd;
            font-size: 16px;
        }
        strong {
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            margin: 30px 0 20px;
            color: #fff;
            background: linear-gradient(135deg, #007bff, #0056d2);
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: background 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #0056d2, #003c9e);
        }
        .footer {
            font-size: 14px;
            color: #999;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>🎉 Job Post Confirmation</h2>

        @if(isset($project['title']))
        <h3>{{ $project['title'] }}</h3>
        @endif

        @if(isset($project['clientUser']['name']))
        <p>Hi <strong>{{ $project['clientUser']['name'] }}</strong>,</p>
        @endif

        <p>Your job post has been successfully created on our platform. Here are the details:</p>

        @if(isset($project['description']))
        <h4>📝 Job Description</h4>
        <p>{!! $project['description'] !!}</p>
        @endif

        <ul>
            @if(isset($project['project_status']))
            <li><strong>Status:</strong> {{ ucfirst($project['project_status_label']) }}</li>
            @endif

            @if(isset($project['budget_type']))
            <li><strong>Budget Type:</strong> {{ ucfirst($project['budget_type_label']) }}</li>
            @endif

            @if(isset($project['hourly_from']) && isset($project['hourly_to']))
            <li><strong>Hourly Rate:</strong> ${{ $project['hourly_from'] }} - ${{ $project['hourly_to'] }} per hour</li>
            @endif

            @if(isset($project['fixed_rate']))
            <li><strong>Fixed Rate:</strong> ${{ $project['fixed_rate'] }}</li>
            @endif

            @if(isset($project['created_at']))
            <li><strong>Posted On:</strong> {{ $project['created_at']->format('F j, Y') }}</li>
            @endif
        </ul>

        <a href="{{ \App\Constants\ProjectConstants::FRONTEND_PATH }}/projects" class="button">🔗 View Job Post</a>

        <p>Thank you for trusting <strong>{{ config('app.name') }}</strong> for your hiring needs. We’re excited to help you find the perfect freelancer!</p
