<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .error-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .error-box {
            background-color: #ffffff;
            border: 2px solid #f1f1f1;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .error-box h1 {
            font-size: 120px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
        }

        .error-box h3 {
            font-size: 36px;
            color: #333;
            margin-bottom: 15px;
        }

        .error-box p {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 25px;
        }

        .btn-primary {
            font-size: 18px;
            padding: 12px 28px;
            border-radius: 50px;
            background-color: #007bff;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="error-box">
            <h1>Oops!</h1>
            <h3>Something went wrong.</h3>
            <p>We couldn't find what you're looking for. Please try again later or go back to the home page.</p>

            <!-- Display error message from session -->
            @if(session('error'))
                <div class="alert alert-danger">
                    <strong>Error:</strong> {{ session('error') }}
                </div>
            @endif

            <a href="{{ url('/') }}" class="btn btn-lg btn-primary btn-back">Go Back to Home</a>
        </div>
    </div>

</body>
</html>
