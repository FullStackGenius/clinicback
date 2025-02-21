<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Routes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            max-width: 1500px;  /* Increased table width */
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn-dashboard {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            display: block;
            width: 200px;
            margin: 0 auto 20px auto;
            font-size: 16px;
        }

        .btn-dashboard:hover {
            background-color: #0056b3;
        }

        .serial-no {
            width: 5%;
        }

        .action-column {
            word-break: break-word;
        }

        @media (max-width: 768px) {
            table, th, td {
                padding: 8px;
            }

            .btn-dashboard {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>API Routes</h1>

        <a href="{{ route('dashboard') }}" class="btn-dashboard">Back to Dashboard</a>

        <table>
            <thead>
                <tr>
                    <th class="serial-no">#</th>
                    <th>Method</th>
                    <th>URI</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($apiRoutes as $index => $route)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $route['method'] }}</td>
                        <td>{{ $route['uri'] }}</td>
                        <td>{{ $route['name'] }}</td>
                        <td class="action-column">{{ $route['action'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
