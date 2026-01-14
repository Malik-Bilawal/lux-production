<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0,0,0,0.12);
        }
        /* HEADER */
        .email-header {
            background: linear-gradient(135deg, #6366f1, #4338ca);
            padding: 35px 20px;
            text-align: center;
            color: #fff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        /* BODY */
        .email-body {
            padding: 40px 35px;
            line-height: 1.7;
            font-size: 15px;
            color: #374151;
        }
        .email-body h2 {
            margin-top: 0;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .email-body p {
            margin: 14px 0;
            font-size: 15px;
            color: #4b5563;
        }
        /* BUTTON */
        .cta-btn {
            display: inline-block;
            margin-top: 28px;
            padding: 14px 30px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: #fff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
            transition: all 0.3s ease;
        }
        .cta-btn:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(67,56,202,0.35);
        }
        /* FOOTER */
        .email-footer {
            background: #f9fafb;
            text-align: center;
            padding: 18px;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 6px 0;
        }
        .email-footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- HEADER -->
        <div class="email-header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <!-- BODY -->
        <div class="email-body">
            <h2>Hi {{ $userName }},</h2>

            <p>{!! nl2br(e($messageText)) !!}</p>

            <p>We’re always here to assist you.  
               If you have any questions, feel free to reach out.</p>

            <a href="{{ url('/') }}" class="cta-btn">Visit Our Website</a>
        </div>

        <!-- FOOTER -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p><a href="{{ url('/') }}">Unsubscribe</a> | <a href="{{ url('/') }}">Contact Support</a></p>
        </div>
    </div>
</body>
</html>
