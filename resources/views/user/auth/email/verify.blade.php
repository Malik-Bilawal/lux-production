<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; background-color: white; margin: auto; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px #ccc;">
        <h2 style="color: #333;">Hello {{ $user->name }},</h2>
        <p style="color: #555;">
            Thank you for registering on our site. Please click the button below to verify your email address:
        </p>
        <p style="text-align: center;">
            <a href="{{ $link }}" style="background-color: #4CAF50; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;">Verify Email</a>
        </p>
        <p style="color: #999;">If you did not create an account, no further action is required.</p>
        <p style="color: #999;">– Team Luorix</p>
    </div>
</body>
</html>
