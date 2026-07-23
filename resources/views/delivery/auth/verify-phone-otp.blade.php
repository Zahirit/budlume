<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify Mobile Number | Budlume</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f6f7f5;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 15px;
        }

        .otp-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }

        .check-icon {
            width: 54px;
            height: 54px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #eef8e9;
            color: #4b9d1c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        h1 {
            margin: 0 0 10px;
            text-align: center;
            font-size: 25px;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            line-height: 1.6;
            color: #555555;
            margin-bottom: 24px;
        }

        .phone-box {
            text-align: center;
            padding: 14px;
            border: 1px solid #dddddd;
            border-radius: 6px;
            background: #fafafa;
            margin-bottom: 16px;
        }

        .phone-box small {
            display: block;
            color: #777777;
            margin-bottom: 5px;
        }

        .phone-box strong {
            font-size: 16px;
        }

        .alert {
            padding: 11px 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .alert-success {
            background: #effbea;
            border: 1px solid #b9dda9;
            color: #347514;
        }

        .alert-error {
            background: #fff0f0;
            border: 1px solid #e8b4b4;
            color: #a12020;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .otp-input {
            width: 100%;
            height: 48px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 8px;
            margin-bottom: 14px;
        }

        button {
            width: 100%;
            height: 42px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .verify-btn {
            border: none;
            background: #4b9d1c;
            color: white;
        }

        .resend-btn {
            margin-top: 12px;
            background: white;
            border: 1px solid #cccccc;
            color: #222222;
        }

        .note {
            margin-top: 22px;
            padding: 12px;
            background: #fafafa;
            font-size: 11px;
            line-height: 1.6;
            color: #666666;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="otp-card">

    <div class="check-icon">✓</div>

    <h1>Verify Your Mobile</h1>

    <div class="subtitle">
        Welcome {{ $user->name }}.<br>
        Please verify your mobile number to complete your
        Delivery Man registration.
    </div>

    <div class="phone-box">
        <small>VERIFICATION CODE SENT TO</small>
        <strong>{{ $user->phone }}</strong>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('delivery.phone.otp.verify') }}">

        @csrf

        <label>6-Digit Verification Code</label>

        <input
            type="text"
            name="otp"
            class="otp-input"
            maxlength="6"
            inputmode="numeric"
            autocomplete="one-time-code"
            required
            autofocus
        >

        <button type="submit" class="verify-btn">
            Verify Mobile Number
        </button>

    </form>

    <form method="POST"
          action="{{ route('delivery.phone.otp.send') }}">

        @csrf

        <button type="submit" class="resend-btn">
            Generate / Resend Verification Code
        </button>

    </form>

    <div class="note">
        After successful mobile verification, your Delivery Man account
        will remain <strong>Pending</strong> until approved by the Budlume administrator.
    </div>

</div>

</body>
</html>