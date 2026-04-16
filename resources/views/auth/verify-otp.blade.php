<!DOCTYPE html>
<html lang="en">
<head>
	<title>Eduport - LMS, Education and Course Theme</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="StackBros">
	<meta name="description" content="Eduport- LMS, Education and Course Theme">

	<!-- Dark mode script -->
	<script>
		const storedTheme = localStorage.getItem('theme');
		const getPreferredTheme = () => storedTheme || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light');
		const setTheme = function (theme) {
			document.documentElement.setAttribute('data-bs-theme', (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : theme);
		}
		setTheme(getPreferredTheme());
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/all.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

	<!-- Theme CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
<main>
    <section class="p-0 d-flex align-items-center position-relative overflow-hidden">
		<div class="container-fluid">
			<div class="row">
				<!-- Left Side -->
				<div class="col-12 col-lg-6 d-md-flex align-items-center justify-content-center bg-primary bg-opacity-10 vh-lg-100">
					<div class="p-3 p-lg-5 text-center">
						<h2 class="fw-bold">Welcome to our largest community</h2>
						<p class="mb-0 h6 fw-light">Let's learn something new today!</p>
						<img src="{{ asset('assets/images/element/02.svg') }}" class="mt-5" alt="">
						<div class="d-sm-flex mt-5 align-items-center justify-content-center">
							<p class="mb-0 h6 fw-light ms-0 ms-sm-3">4k+ Students joined us, now it's your turn.</p>
						</div>
					</div>
				</div>
				<!-- Right Side / Form -->
				<div class="col-12 col-lg-6 m-auto">
					<div class="row ">
                        <div class="col-12 col-lg-8 m-auto mb-4">
							<img src="{{ asset('assets/images/logo.webp') }}" class="text-center align-items-center" alt=""> 
						</div>
						<div class="col-sm-10 col-xl-8 m-auto">
							<h3 class="fs-2">Verify OTP</h3>
							<p class="m-0">We have sent a verification code to your email.</p>
                            <p class="mb-4">Please enter the OTP below to continue.</p>
							<!-- Otp Form -->
                            
							 <form method="POST" action="{{ route('verify.otp') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
								<!-- Email -->
								<div class="mb-4">
									<label for="email" class="form-label">Enter OTP *</label>
									<div class="input-group input-group-lg">
										<span class="input-group-text bg-light rounded-start border-0 text-secondary px-3">
											<i class="bi bi-shield-lock-fill"></i>
										</span>
                                        <input type="text" name="otp" class="form-control border-0 bg-light rounded-end ps-1 @error('otp') is-invalid @enderror" placeholder="Enter 6-digit OTP" value="{{ old('otp') }}" required>
									</div>
									@error('otp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
								</div>						
								<!-- Submit -->
								<div class="d-grid">
									<button type="submit" class="btn btn-primary mb-0">Verify OTP</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div> <!-- row end -->
		</div>
	</section>

</main>

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i></div>

<!-- Scripts -->
<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/functions.js') }}"></script>
</body>
</html>

