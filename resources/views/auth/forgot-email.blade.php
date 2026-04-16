<!DOCTYPE html>
<html lang="en">
 <head>
      <meta charset="utf-8">
      <meta content="width=device-width, initial-scale=1.0" name="viewport">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Designer Portal') }}</title>
      <!-- Favicons -->
      <link href="{{ asset('assets/img/favicon.ico') }}" rel="icon">
      <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
      <!-- Fonts -->
       <meta name="user-id" content="{{ auth()->id() }}">
      <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
      <!-- Vendor CSS -->
      <link href="{{ asset('assets/css/vendors.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/emoji-mart.css') }}" rel="stylesheet">
   </head>

<body>
<body class="bg-white">
    <!-- begin app -->
    <div class="app">
        <!-- begin app-wrap -->
        <div class="app-wrap">
            <!-- begin pre-loader -->
            <div class="loader">
                <div class="h-100 d-flex justify-content-center">
                    <div class="align-self-center">
                        <img src="assets/img/loader/loader.svg" alt="loader">
                    </div>
                </div>
            </div>
            <!-- end pre-loader -->

            <!--start login contant-->
            <div class="app-contant">
                <div class="bg-white">
                    <div class="container-fluid p-0">
                        <div class="row no-gutters">
                            <div class="col-sm-6 col-lg-5 col-xl-3  align-self-center order-2 order-sm-1">
                                <div class="d-flex align-items-center h-100-vh">
                                    <div class="login p-50 w-100">
                                        <div class="bg-img">
                                            <img src="assets/img/avtar/01.jpg" class="img-fluid" alt="Clients-01">
                                        </div>
                                        <p class="mt-4 mb-0">You must enter your password to access admin screen</p>
                                        <form method="POST" action="{{ route('send.otp') }}">
                                            @csrf
                                        <div class="input-group my-3">
                                            <input type="email" id="email" name="email" class="form-control border-0 bg-light rounded-end ps-1 @error('email') is-invalid @enderror" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
                                            @error('email')
										<div class="text-danger small mt-1">{{ $message }}</div>
									@enderror
                                        </div>
										 <button type="submit" class="btn btn-primary text-uppercase">Send OTP</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-9 col-lg-7 bg-gradient o-hidden order-1 order-sm-2">
                                <div class="row align-items-center h-100">
                                    <div class="col-7 mx-auto ">
                                        <img class="img-fluid" src="assets/img/bg/login.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end login contant-->
        </div>
        <!-- end app-wrap -->
    </div>
    <!-- end app -->



    <!-- plugins -->
    <script src="assets/js/vendors.js"></script>

    <!-- custom app -->
    <script src="assets/js/app.js"></script>
</body>
</html>
