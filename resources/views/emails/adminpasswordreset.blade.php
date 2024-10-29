<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Dear {{ $userName }},</p>
        <p>We heard that you lost your password. Sorry about that!</p>
        <p>But don’t worry! You can use the button below to reset your password:</p>
        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" class="button">RESET PASSWORD</a>
        </div>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Thank you,<br>The Testlink Team</p>
    </div>
</body>
</html>