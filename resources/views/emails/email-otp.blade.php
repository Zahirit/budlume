<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budlume Email Verification</title>
</head>

<body style="margin:0; padding:0; background:#f4f4f4; font-family:Arial, sans-serif;">

    <div style="
        max-width:600px;
        margin:30px auto;
        background:#ffffff;
        padding:35px;
        border-radius:8px;
        text-align:center;
    ">

        <h1 style="margin-bottom:10px;">
            Budlume
        </h1>

        <h2 style="color:#333;">
            Verify Your Email
        </h2>

        <p style="color:#666; font-size:16px;">
            Please use the verification code below to verify your email address.
        </p>

        <div style="
            font-size:32px;
            font-weight:bold;
            letter-spacing:8px;
            margin:30px 0;
            padding:18px;
            background:#f5f5f5;
            border-radius:6px;
        ">
            {{ $otp }}
        </div>

        <p style="color:#666;">
            This verification code will expire shortly.
        </p>

        <p style="color:#999; font-size:13px; margin-top:30px;">
            If you did not request this verification code,
            you can safely ignore this email.
        </p>

    </div>

</body>
</html>