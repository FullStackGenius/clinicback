<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 50px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #333333; text-align: center;">Welcome, {{ $user->name }}</h2>
        <p style="color: #555555; text-align: center;">
            Thank you for signing up! Please verify your email address by clicking the button below.
        </p>
        <div style="text-align: center; margin: 20px 0;">
            @php
            $encryptedId = \Illuminate\Support\Facades\Crypt::encrypt($user->id);
        @endphp
            <a href="{{ route('verify-your-email',$encryptedId )}}" style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                Verify Email
            </a>
        </div>
        <p style="color: #555555; text-align: center;">
            If you did not create an account, you can safely ignore this email.
        </p>
        <p style="color: #999999; font-size: 12px; text-align: center;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
