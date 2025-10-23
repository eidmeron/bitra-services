<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
        .unsubscribe {
            margin-top: 20px;
        }
        .unsubscribe a {
            color: #6c757d;
            text-decoration: none;
        }
        .unsubscribe a:hover {
            text-decoration: underline;
        }
        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Bitra Services</div>
            <h1>{{ $campaign->subject }}</h1>
        </div>

        <div class="content">
            {!! $campaign->content !!}
        </div>

        <div class="footer">
            <p>Detta är ett e-postmeddelande från Bitra Services.</p>
            <p>Om du inte längre vill ta emot dessa e-postmeddelanden kan du <a href="{{ $unsubscribeUrl }}">avsluta prenumerationen här</a>.</p>
            
            <div class="unsubscribe">
                <p>
                    <strong>Bitra Services</strong><br>
                    Sveriges ledande plattform för tjänstebokning<br>
                    <a href="{{ url('/') }}">{{ url('/') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
