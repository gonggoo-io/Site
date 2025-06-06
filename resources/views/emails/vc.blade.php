<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>이메일 인증</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header {
            padding: 24px;
            text-align: center;
        }
        .header img {
            width: 140px;
            margin-bottom: 8px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #38394B;
            margin: 0 0 12px;
        }
        .body {
            padding: 24px;
            color: #000000;
            font-size: 15px;
            line-height: 1.6;
            text-align: center;
        }
        .code-button {
            display: block;
            margin: 24px auto;
            color: #38394B;
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            padding: 10px 0;
            border-radius: 0;
            text-decoration: none;
            width: fit-content;
            letter-spacing: 4px;
            background: none;
        }
        .footer {
            font-size: 12px;
            color: #9CA3AF;
            padding: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">[공구] 이메일 인증을 완료해주세요.</div>
        </div>
        <div class="body">
            아래 발급된 이메일 인증번호를 복사하거나 직접 입력하여 인증을 완료해주세요. <br>
            개인정보 보호를 위해 인증번호는 5분 간 유효합니다. 
            <a href="#" class="code-button">{{ $code }}</a>
        </div>  
        <div class="footer">
            본 메일은 발신 전용이며, 회신하실 수 없습니다.<br>
            © 2025 gongoo. All rights reserved.
        </div>
    </div>
</body>
</html> 