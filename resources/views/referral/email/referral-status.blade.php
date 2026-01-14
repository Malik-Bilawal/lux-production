<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Referral Status</title>
  <style>
    body {
      font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f3f4f6;
      color: #111827;
    }
    .container {
      max-width: 640px;
      margin: 40px auto;
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .header {
      background: linear-gradient(135deg, #111827, #2563eb);
      padding: 30px 20px;
      text-align: center;
    }
    .header h1 {
      margin: 0;
      font-size: 22px;
      color: #fff;
      letter-spacing: 0.5px;
    }
    .body {
      padding: 40px 30px;
      line-height: 1.7;
      font-size: 16px;
      color: #374151;
    }
    .body h2 {
      margin-top: 0;
      font-size: 20px;
      color: #111827;
    }
    .highlight {
      font-weight: 600;
      color: #2563eb;
    }
    .btn {
      display: inline-block;
      padding: 14px 28px;
      margin-top: 24px;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: #fff !important;
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
      transition: 0.3s ease;
    }
    .btn:hover {
      background: linear-gradient(135deg, #1d4ed8, #1e40af);
      box-shadow: 0 6px 14px rgba(29, 78, 216, 0.5);
    }
    .footer {
      padding: 25px;
      text-align: center;
      background: #f9fafb;
      font-size: 13px;
      color: #6b7280;
      border-top: 1px solid #e5e7eb;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Luxorix Referral Program</h1>
    </div>

    <!-- Body -->
    <div class="body">
      <h2>Hello {{ $referral->name }},</h2>

      @if($status == "approved")
        <p>
          <span class="highlight">Congratulations!</span> 
          Your referral application has been <strong>Approved</strong>.
        </p>
        <p>
          You are now an official member of the Luxorix Referral Program.
        </p>
        <p style="margin-top: 24px;">
          To get started and find your referral code, 
          please log in to your dashboard:
        </p>

        <div style="background: #f3f4f6; padding: 14px 20px; border-radius: 8px; text-align: center;">
          <p style="margin: 0; font-size: 15px; color: #374151;">Go to our website and log in:</p>
          <strong style="font-size: 18px; color: #111827; letter-spacing: 0.5px;">
            luxorix.com/referral/login
          </strong>
        </div>
        
        <p style="margin-top: 24px;">
          Use the same email and password you used to sign up. Welcome aboard!
        </p>

      @else
        <p> 
          Thank you for your interest. Unfortunately, your referral application 
          has been <strong>Declined</strong>.
        </p>
        <p>
          At this time, your application did not meet our current requirements. 
          We appreciate your understanding.
        </p>
      @endif

      <p style="margin-top: 30px;">Regards,<br><strong>Luxorix Team</strong></p>
    </div>

    <!-- Footer -->
    <div class="footer">
      © {{ date('Y') }} Luxorix. All rights reserved.<br>
      You are receiving this email because you applied for our referral program.
    </div>
  </div>
</body>
</html>
