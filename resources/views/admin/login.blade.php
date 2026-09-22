<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Login - Hypeline</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        body {
            min-height: 100vh;
            background: #f5f5f5;
            color: #111;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-name {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 3px;
            margin-bottom: 5px;
        }

        .brand-subtitle {
            font-size: 12px;
            letter-spacing: 2px;
            color: #777;
            text-transform: uppercase;
        }

        .login-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-description {
            text-align: center;
            color: #777;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control {
            height: 48px;
            border-radius: 7px;
            border: 1px solid #d5d5d5;
        }

        .form-control:focus {
            border-color: #111;
            box-shadow: 0 0 0 0.15rem rgba(0, 0, 0, 0.08);
        }

        .login-button {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 7px;
            background: #111;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .5px;
            transition: .2s;
        }

        .login-button:hover {
            background: #333;
        }

        .error-message {
            background: #fff1f1;
            border: 1px solid #f0caca;
            color: #b42318;
            border-radius: 7px;
            padding: 11px 14px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .footer-text {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 25px;
        }

        @media (max-width: 576px) {

            .login-card {
                padding: 30px 22px;
            }

            .brand-name {
                font-size: 25px;
            }

        }

    </style>

</head>


<body>

    <div class="login-wrapper">

        <div class="login-card">


            {{-- BRAND --}}

            <div class="brand">

                <div class="brand-name">
                    HYPELINE
                </div>

                <div class="brand-subtitle">
                    Quality • Trust • Style
                </div>

            </div>


            {{-- TITLE --}}

            <div class="login-title">
                Admin Login
            </div>

            <div class="login-description">
                Sign in to manage your Hypeline store.
            </div>


            {{-- VALIDATION ERROR --}}

            @if($errors->any())

                <div class="error-message">

                    @foreach($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            @endif


            {{-- LOGIN FORM --}}

            <form
                method="POST"
                action="{{ route('admin.login.submit') }}"
            >

                @csrf


                {{-- EMAIL --}}

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        placeholder="admin@hypeline.com"
                        required
                        autofocus
                    >

                </div>


                {{-- PASSWORD --}}

                <div class="mb-4">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                {{-- LOGIN BUTTON --}}

                <button
                    type="submit"
                    class="login-button"
                >
                    LOGIN TO ADMIN PANEL
                </button>

            </form>


            <div class="footer-text">

                © {{ date('Y') }} HYPELINE

            </div>

        </div>

    </div>

</body>

</html>