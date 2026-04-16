<!-- resources/views/auth/verify_otp.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify OTP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="bg-white">

<div class="app">
    <div class="app-wrap">

        <div class="app-contant">
            <div class="bg-white">
                <div class="container-fluid p-0">
                    <div class="row no-gutters">

                        <!-- LEFT SIDE FORM -->
                        <div class="col-sm-6 col-lg-5 col-xxl-3 align-self-center">
                            <div class="d-flex align-items-center h-100-vh">
                                <div class="login p-50">

                                    <h1 class="mb-2">OTP Verification</h1>
                                    <p>We have sent a one-time password (OTP) to your email address.</p>
                                    <p><strong>Email Address:</strong> {{ $email }}</p>

                                    @if($errors->any())
                                        <div class="text-danger mb-2">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('verify.otp') }}">
                                    @csrf

                                    <input type="hidden" name="email" value="{{ session('email') }}">

                                    <p>Email: {{ session('email') }}</p>

                                    <div class="input-group my-3">
                                        <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>

                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-unlock"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success">Verify OTP</button>
                                </form>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT SIDE IMAGE -->
                        <div class="col-sm-6 col-lg-7 bg-gradient o-hidden">
                            <div class="row align-items-center h-100">
                                <div class="col-7 mx-auto">
                                    <img class="img-fluid"
                                         src="{{ asset('assets/img/bg/login.svg') }}"
                                         alt="">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('assets/js/vendors.js')}}"></script>
<script src="{{ asset('assets/js/app.js')}}"></script>

</body>
</html>