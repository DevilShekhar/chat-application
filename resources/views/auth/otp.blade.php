<!-- resources/views/auth/otp.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>OTP Login</title>
</head>
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
                                        
                                        <h4 class="mt-4">OTP Login</h4>
                                        @if(session('success'))
                                            <p style="color:green;">{{ session('success') }}</p>
                                        @endif
                                        @if($errors->any())
                                            <p style="color:red;">{{ $errors->first() }}</p>
                                        @endif
                                        <span class="mt-1"><i class="fa fa-lock"></i> Locked</span>
                                        <p class="mt-4 mb-0">You must enter your password to access admin screen</p>
                                       {{-- Email Form --}}
                                        @if(!session('otp_sent'))
                                        <form method="POST" action="{{ route('send.otp') }}">
                                            @csrf

                                            <div class="input-group my-3">
                                                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>

                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-unlock"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Send OTP</button>
                                        </form>
                                        @endif
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




</body>
</html>