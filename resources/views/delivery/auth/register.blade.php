<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Delivery Man Registration | Budlume</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        .register-wrapper {
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
        }

        .register-card {
            background: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 35px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .brand {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .brand p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .section-title {
            margin: 28px 0 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e5e5;
            font-size: 17px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            height: 46px;
            padding: 10px 13px;
            border: 1px solid #d5d5d5;
            border-radius: 5px;
            font-size: 15px;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #333;
        }

        input[type="file"].form-control {
            height: auto;
            padding: 10px;
        }

        .help-text {
            margin-top: 6px;
            color: #777;
            font-size: 12px;
            line-height: 1.5;
        }

        .error {
            margin-top: 6px;
            color: #c62828;
            font-size: 13px;
        }

        .alert-error {
            margin-bottom: 20px;
            padding: 12px 15px;
            border: 1px solid #f1b8b8;
            background: #fff1f1;
            border-radius: 5px;
            color: #a61b1b;
            font-size: 14px;
        }

        .btn-register {
            width: 100%;
            border: 0;
            border-radius: 5px;
            padding: 14px 20px;
            background: #111;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-register:hover {
            opacity: 0.9;
        }

        .notice {
            margin-top: 25px;
            padding: 14px;
            background: #f8f8f8;
            border-radius: 5px;
            color: #555;
            font-size: 13px;
            line-height: 1.6;
        }
    </style>

</head>

<body>

<div class="register-wrapper">

    <div class="register-card">

        <div class="brand">

            <h1>Budlume</h1>

            <p>Delivery Man Registration</p>

        </div>

        @if ($errors->any())

            <div class="alert-error">
                Please correct the information below.
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('delivery.register.store') }}"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="section-title">
                Account Information
            </div>


            {{-- Full Name --}}

            <div class="form-group">

                <label for="name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >

                @error('name')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Email --}}

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                >

                @error('email')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Mobile --}}

            <div class="form-group">

                <label for="phone">
                    Mobile Number
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone') }}"
                    placeholder="Enter mobile number"
                    required
                >

                @error('phone')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Password --}}

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    required
                >

                @error('password')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Confirm Password --}}

            <div class="form-group">

                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    required
                >

            </div>


            <div class="section-title">
                Delivery Verification Documents
            </div>


            {{-- Driving License Number --}}

            <div class="form-group">

                <label for="driving_license_number">
                    Driving License Number
                </label>

                <input
                    type="text"
                    id="driving_license_number"
                    name="driving_license_number"
                    class="form-control"
                    value="{{ old('driving_license_number') }}"
                    required
                >

                @error('driving_license_number')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- Driving License Photo --}}

            <div class="form-group">

                <label for="driving_license_photo">
                    Clear Photo of Driving License
                </label>

                <input
                    type="file"
                    id="driving_license_photo"
                    name="driving_license_photo"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    required
                >

                <div class="help-text">
                    Upload a clear JPG, JPEG or PNG image.
                    Maximum file size: 5 MB.
                </div>

                @error('driving_license_photo')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- SIN Number --}}

            <div class="form-group">

                <label for="sin_number">
                    SIN Number
                </label>

                <input
                    type="password"
                    id="sin_number"
                    name="sin_number"
                    class="form-control"
                    autocomplete="off"
                    required
                >

                <div class="help-text">
                    Your SIN is treated as confidential information
                    and stored securely.
                </div>

                @error('sin_number')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <button
                type="submit"
                class="btn-register"
            >
                Create Delivery Account
            </button>

        </form>


        <div class="notice">

            After registration, you must verify your mobile number.

            Your delivery account will remain
            <strong>Pending</strong>
            until approved by the Budlume administrator.

        </div>

    </div>

</div>

</body>

</html>