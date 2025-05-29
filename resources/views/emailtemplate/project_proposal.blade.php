<!DOCTYPE html>
<html>
<head>
    <title>New Project Proposal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            font-size: 20px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            color: #333;
        }
        .section {
            margin-bottom: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .section h3 {
            margin-top: 0;
            color: #007bff;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            font-size: 12px;
            color: #777;
        }
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">New Project Proposal</div>
        
        <div class="content">
            <p>Hi {{ $project->clientUser->name }},</p>
            <p>You have received a new proposal for your project <strong>{{ $project->title }}</strong>.</p>

            <div class="section">
                <h3>Freelancer Details</h3>
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <img src="{{ $freelancer->profile_image_path }}" alt="Profile Image" class="profile-img">
                        </td>
                        <td>
                            <strong>{{ $freelancer->name }} {{ $freelancer->last_name }}</strong> <br>
                         
                            <small>Email: {{ $freelancer->email }}</small>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h3>Proposal Details</h3>
                {{-- <p><strong>Bid Amount:</strong> ${{ $proposal->bid_amount }}</p> --}}
                <p><strong>Application note:</strong></p>
                <p>{{ $proposal->cover_letter }}</p>
            </div>

            <div class="section">
                <h3>Project Details</h3>
                <p><strong>Title:</strong> {{ $project->title }}</p>
                <p><strong>Description:</strong> {{ $project->description }}</p>
                <p><strong>Budget Type:</strong> {{ ucfirst($project->budget_type_label) }}</p>
                @if($project->budget_type == 1)
                    <p><strong>Hourly Rate:</strong> ${{ $project->hourly_from }} - ${{ $project->hourly_to }}</p>
                @else
                    <p><strong>Fixed Price:</strong> ${{ $project->fixed_rate }}</p>
                @endif
                <p><strong>Scope:</strong> {{ $project->projectScope->name }}</p>
                <p><strong>Duration:</strong> {{ $project->projectDuration->name }}</p>
            </div>

            {{-- <p style="text-align: center;">
                <a href="{{ url('/projects/' . $project->id) }}" class="btn">View Project</a>
            </p> --}}
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
        </div>
    </div>

</body>
</html>
