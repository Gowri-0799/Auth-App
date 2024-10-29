<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .reset-button {
            display: inline-block;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            color: #333333;
        }
        .footer-logo {
            display: block;
            margin: 20px auto 0;
            width: 100px;
        }
    </style>
</head>
<body style="background-color: #f8f9fa; padding: 20px;">
    <div class="container">
        <p>Dear {{ $userName }},</p>
        <p>We heard that you lost your password. Sorry about that!</p>
        <p>But don’t worry! You can use the following button to reset your password:</p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $resetUrl }}" target="_blank" class="reset-button">RESET PASSWORD</a>
        </div>

        <p>Yours,<br>TestLink Technologies LLC</p>

        <img src="/assets/images/Ln_logo.png" alt="Testlink Logo" class="footer-logo">
    </div>
</body>
</html>
