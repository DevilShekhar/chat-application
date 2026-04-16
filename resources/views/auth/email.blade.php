<!-- resources/views/auth/email.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Email</title>
</head>
<body>

<h2>Enter Email</h2>

@if($errors->any())
    <p style="color:red;">{{ $errors->first() }}</p>
@endif

<form method="POST" action="{{ route('send.otp') }}">
    @csrf
    <input type="email" name="email" placeholder="Enter Email" required>
    <button type="submit">Send OTP</button>
</form>

</body>
</html>