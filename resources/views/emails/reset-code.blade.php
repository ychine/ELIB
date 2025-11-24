<!DOCTYPE html>
<html>
<head>
    <style>
        body {font-family:Arial,sans-serif;background:#f9f9f9;padding:20px;}
        .wrapper {max-width:560px;margin:auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.1);}
        .header {background:#16A34A;color:#fff;padding:30px;text-align:center;}
        .header img {height:48px;margin-bottom:8px;}
        .body {padding:40px 30px;text-align:center;color:#333;}
        .code {font-size:36px;font-weight:bold;letter-spacing:12px;color:#16A34A;background:#f0fdf4;padding:15px;border:2px dashed #16A34A;border-radius:12px;display:inline-block;margin:20px 0;}
        .footer {background:#f4f4f4;padding:20px;font-size:12px;color:#777;text-align:center;}
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="https://raw.githubusercontent.com/ychine/ELIB/main/resources/images/ISUStudyGopng.png" alt="ISU StudyGo">
        <h2>Password Reset Request</h2>
    </div>
    <div class="body">
        <p>Hello!</p>
        <p>Use the 6-digit code below to reset your ISU StudyGo password:</p>
        <div class="code">
            @foreach(str_split($code, 3) as $part)
                {{ $part }}@if(!$loop->last) @endif
            @endforeach
        </div>
        <p>This code expires in <strong>15 minutes</strong>.</p>
        <p>If you didn’t request a password reset, you can safely ignore this email.</p>
    </div>
    <div class="footer">
        © {{ date('Y') }} ISU StudyGo. All rights reserved.
    </div>
</div>
</body>
</html>

