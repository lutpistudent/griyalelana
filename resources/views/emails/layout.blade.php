<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Griya Lelana')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f0eb; color: #2d2418; line-height: 1.6; }
        .email-wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .email-card { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(45, 36, 24, 0.08); }
        .email-header { background: linear-gradient(135deg, #2d2418 0%, #4a3728 100%); padding: 32px 24px; text-align: center; }
        .email-header h1 { color: #d4a574; font-size: 24px; font-weight: 800; margin-bottom: 4px; }
        .email-header p { color: rgba(212, 165, 116, 0.7); font-size: 13px; }
        .email-body { padding: 32px 24px; }
        .email-body h2 { font-size: 20px; color: #2d2418; margin-bottom: 8px; }
        .email-body .subtitle { color: #8a7968; font-size: 14px; margin-bottom: 24px; }
        .info-box { background: #faf6f1; border: 1px solid #e8ddd0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0e8de; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #8a7968; }
        .info-value { color: #2d2418; font-weight: 600; }
        .btn { display: inline-block; background: #d4a574; color: #1a1612; font-weight: 700; font-size: 14px; padding: 12px 28px; border-radius: 10px; text-decoration: none; margin-top: 16px; }
        .btn-danger { background: #e74c3c; color: #ffffff; }
        .email-footer { padding: 20px 24px; text-align: center; border-top: 1px solid #f0e8de; }
        .email-footer p { color: #b0a494; font-size: 12px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-card">
            <div class="email-header">
                <h1>Griya Lelana</h1>
                <p>Hunian Nyaman, Harga Terjangkau</p>
            </div>
            <div class="email-body">
                @yield('body')
            </div>
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} Griya Lelana. Email ini dikirim secara otomatis.</p>
            </div>
        </div>
    </div>
</body>
</html>
