<!DOCTYPE html>
<html>
<head>
   <title>Reset Password OTP</title>
   <style>
       body {
           font-family: Arial, sans-serif;
           line-height: 1.6;
           color: #333;
           max-width: 600px;
           margin: 0 auto;
           padding: 20px;
       }

       .email-container {
           background: #f9f9f9;
           border-radius: 10px;
           padding: 30px;
           box-shadow: 0 2px 5px rgba(0,0,0,0.1);
       }

       .header {
           text-align: center;
           margin-bottom: 30px;
       }

       .logo {
           max-width: 150px;
           margin-bottom: 20px;
       }

       .greeting {
           font-size: 20px;
           margin-bottom: 20px;
           color: #2d3748;
       }

       .otp-container {
           background: #ffffff;
           border-radius: 5px;
           padding: 20px;
           text-align: center;
           margin: 20px 0;
           border: 1px solid #e2e8f0;
       }

       .otp-code {
           font-size: 32px;
           font-weight: bold;
           color: #4a5568;
           letter-spacing: 5px;
           margin: 10px 0;
       }

       .note {
           font-size: 14px;
           color: #718096;
           margin-top: 20px;
           text-align: center;
       }

       .footer {
           margin-top: 30px;
           text-align: center;
           font-size: 12px;
           color: #a0aec0;
       }
   </style>
</head>
<body>
   <div class="email-container">
       <div class="header">
           <!-- Ganti dengan logo perusahaan Anda -->
           <img src="your-logo.png" alt="Company Logo" class="logo">
           <h1>Reset Password</h1>
       </div>

       <div class="greeting">
           Hello, {{ $user->name }}
       </div>

       <p>We received a request to reset your password. Please use the OTP code below to proceed:</p>

       <div class="otp-container">
           <div>Your OTP Code</div>
           <div class="otp-code">{{ $otp }}</div>
           <div>This code will expire in 15 minutes</div>
       </div>

       <p class="note">
           If you didn't request a password reset, please ignore this email or contact support if you have concerns.
       </p>

       <div class="footer">
           <p>This is an automated email, please do not reply.</p>
           <p>&copy; 2024 Your Company Name. All rights reserved.</p>
       </div>
   </div>
</body>
</html>
