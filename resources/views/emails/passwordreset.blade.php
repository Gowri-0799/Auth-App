<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2>Dear {{ $userName }},</h2>
        <p>We heard that you lost your password. Sorry about that!</p>
        <p>But don’t worry! You can use the button below to reset your password:</p>

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ $resetUrl }}" 
               style="display: inline-block; padding: 12px 24px; font-size: 16px; color: #ffffff; background-color: #007bff; text-decoration: none; border-radius: 5px;">
                RESET PASSWORD
            </a>
        </div>

        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Thank you,<br>The Testlink Team</p>
    </div>
</body>
</html>
