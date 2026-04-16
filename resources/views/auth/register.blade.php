<!DOCTYPE html>
<html lang="en">
<head>
  <title>Eduport - Register</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

<main>
  <section class="p-0 d-flex align-items-center position-relative overflow-hidden">
    <div class="container-fluid">
      <div class="row">

        <!-- Left side -->
        <div class="col-12 col-lg-6 d-md-flex align-items-center justify-content-center bg-primary bg-opacity-10 vh-lg-100">
          <div class="p-3 p-lg-5 text-center">
            <h2 class="fw-bold">Welcome to our largest community</h2>
            <p class="mb-0 h6 fw-light">Let's learn something new today!</p>
            <img src="{{ asset('assets/images/element/02.svg') }}" class="mt-5" alt="">
            <div class="d-sm-flex mt-5 align-items-center justify-content-center">
              <ul class="avatar-group mb-2 mb-sm-0">
                <li class="avatar avatar-sm"><img class="avatar-img rounded-circle" src="{{ asset('assets/images/avatar/01.jpg') }}" alt="avatar"></li>
                <li class="avatar avatar-sm"><img class="avatar-img rounded-circle" src="{{ asset('assets/images/avatar/02.jpg') }}" alt="avatar"></li>
                <li class="avatar avatar-sm"><img class="avatar-img rounded-circle" src="{{ asset('assets/images/avatar/03.jpg') }}" alt="avatar"></li>
                <li class="avatar avatar-sm"><img class="avatar-img rounded-circle" src="{{ asset('assets/images/avatar/04.jpg') }}" alt="avatar"></li>
              </ul>
              <p class="mb-0 h6 fw-light ms-0 ms-sm-3">4k+ Students joined us, now it's your turn.</p>
            </div>
          </div>
        </div>

        <!-- Right side (Form) -->
        <div class="col-12 col-lg-6 m-auto">
          <div class="row my-5">
            <div class="col-sm-10 col-xl-8 m-auto">

              <img src="{{ asset('assets/images/element/03.svg') }}" class="h-40px mb-2" alt="">
              <h2>Sign up for your account!</h2>
              <p class="lead mb-4">Nice to see you! Please Sign up with your account.</p>

              <!-- Registration Form -->
              <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                  <label for="name" class="form-label">Name *</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-user"></i></span>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-control border-0 bg-light @error('name') is-invalid @enderror"
                           placeholder="Full Name" required>
                  </div>
                  @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                  <label for="email" class="form-label">Email address *</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control border-0 bg-light @error('email') is-invalid @enderror"
                           placeholder="E-mail" required>
                  </div>
                  @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                  <label for="password" class="form-label">Password *</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control border-0 bg-light @error('password') is-invalid @enderror"
                           placeholder="*********" required>
                  </div>
                  @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                  <label for="password_confirmation" class="form-label">Confirm Password *</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control border-0 bg-light" placeholder="*********" required>
                  </div>
                </div>

                <!-- Terms -->
                <div class="mb-4 form-check">
                  <input class="form-check-input" type="checkbox" id="checkbox-1" required>
                  <label class="form-check-label" for="checkbox-1">By signing up, you agree to the <a href="#">terms of service</a></label>
                </div>

                <!-- Register Button -->
                <div class="d-grid">
                  <button class="btn btn-primary" type="submit">Sign Up</button>
                </div>
              </form>

              <!-- Divider -->
             

              <!-- Login link -->
              <div class="mt-4 text-center">
                <span>Already have an account? <a href="{{ route('login') }}">Sign in here</a></span>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>

<!-- JS Scripts -->
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/functions.js') }}"></script>

</body>
</html>
