<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Delivery Account Pending | Budlume</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7f7;
            font-family: Arial, sans-serif;
            color: #111;
        }

        .pending-card {
            width: 100%;
            max-width: 460px;
            margin: 20px;
            padding: 40px 32px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }

        .check {
            width: 58px;
            height: 58px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef8e8;
            color: #49a719;
            font-size: 32px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 26px;
        }

        .verified {
            color: #49a719;
            font-weight: bold;
            margin-bottom: 22px;
        }

        .message {
            line-height: 1.7;
            font-size: 14px;
            color: #555;
        }

        .status-box {
            margin: 25px 0;
            padding: 16px;
            background: #fff8e5;
            border: 1px solid #f1d98b;
            border-radius: 5px;
        }

        .status-label {
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
        }

        .status {
            margin-top: 6px;
            font-size: 18px;
            font-weight: bold;
            color: #b77900;
        }

        .login-btn {
            display: block;
            width: 100%;
            padding: 13px;
            margin-top: 22px;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
        }

        .note {
            margin-top: 18px;
            font-size: 12px;
            line-height: 1.6;
            color: #777;
        }
    </style>
</head>

<body>

<div class="pending-card">

    <div class="check">✓</div>

    <h1>Mobile Verified!</h1>

    <div class="verified">
        Verification completed successfully.
    </div>

    <p class="message">
        Thank you for registering as a Budlume Delivery Man.
        Your mobile number has been verified successfully.
    </p>

    <div class="status-box">

        <div class="status-label">
            Account Status
        </div>

        <div class="status">
            Pending Admin Approval
        </div>

    </div>

    <p class="message">
        Your account must be reviewed and approved by the
        Budlume administrator before you can start accepting deliveries.
    </p>

    <a href="{{ route('login') }}" class="login-btn">
        Go to Login
    </a>

    <div class="note">
        After your account is approved, you can log in using the
        email address and password you registered with.
    </div>

</div>

</body>
</html>