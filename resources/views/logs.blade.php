<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Log Viewer</title>
    <style>
        /* Full Page Layout */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1100px;
            height: 80%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            box-sizing: border-box;
        }

        h1 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .log-container {
            background-color: #fafafa;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
            overflow-y: auto;
            flex-grow: 1;
            margin-bottom: 20px;
            height: 60%;  /* Increased height */
            white-space: pre-wrap;
        }

        pre {
            font-size: 18px; /* Increased font size */
            color: #444;
            white-space: pre-wrap;
            word-wrap: break-word;
            margin: 0;
        }

        /* Add margin to separate content */
        .line-break {
            margin-bottom: 20px;
        }

        /* Back to Home Button */
        .back-btn {
            padding: 15px 25px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Laravel Log Viewer</h1>

        <div class="log-container line-break">
            <pre>{!! $logs !!}</pre>
        </div>

        <div class="line-break">
            <a href="{{ url('/dashboard') }}" class="back-btn">Back to Home</a>
        </div>
    </div>
</body>
</html>
