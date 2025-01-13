<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
</head>
<body>
    <p>Hello, {{ $user->name }}</p>
    <p>Your OTP code is: <strong>{{ $user->otp_register }}</strong></p>
</body>
</html>
