<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center;">
            <h2>Liftr</h2>
        </div>
        <div style="padding: 20px; border: 1px solid #e9ecef;">
            {!! $content !!}
        </div>
        <div style="background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d;">
            <p>© {{ date('Y') }} Liftr. All rights reserved.</p>
        </div>
    </div>
</body>
</html>