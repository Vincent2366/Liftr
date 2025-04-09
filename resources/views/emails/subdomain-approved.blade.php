<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subdomain Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a6cf7;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .credentials {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #4a6cf7;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Subdomain Has Been Approved!</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $subdomainRequest->user->name ?? '' }},</p>
        
        <p>We're pleased to inform you that your subdomain request has been approved. Your new subdomain is now active and ready to use.</p>
        
        <div class="credentials">
            <p><strong>Subdomain:</strong> {{ $subdomainRequest->subdomain }}.localhost</p>
            <p><strong>Email:</strong> {{ $subdomainRequest->user->email ?? '' }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
        </div>
        
        <p><strong>Important:</strong> Please check your inbox for a verification email. You need to verify your email address before you can fully access your account.</p>
        
        <p>You can log in to your subdomain using the credentials above after verifying your email. We recommend changing your password after your first login for security purposes.</p>
        
        <a href="http://{{ $subdomainRequest->subdomain }}.localhost:8000/login" class="button">Visit Your Subdomain</a>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Thank you for choosing Liftr!</p>
        
        <p>Best regards,<br>The Liftr Team</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} Liftr. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>
</html>

