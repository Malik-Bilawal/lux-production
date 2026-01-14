<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Montserrat:wght@300;400;500&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
    
    <style>
        /* RESET */
        body { margin: 0; padding: 0; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        
        /* THEME MAPPING (Inline Fallbacks) */
        .body-bg { background-color: #0f0f0f; } /* theme.page */
        .content-bg { background-color: #1a1a1a; } /* theme.surface */
        .text-primary { color: #D4AF37; } /* theme.primary */
        .text-content { color: #FFFFFF; } /* theme.content */
        .text-muted { color: #9CA3AF; } /* theme.muted */
        
        /* TYPOGRAPHY */
        h1, h2, h3 { font-family: 'Cinzel', serif; margin: 0; }
        p, a, span, td { font-family: 'Montserrat', sans-serif; }
        
        /* UTILITIES */
        .gold-divider {
            height: 1px;
            background: #D4AF37;
            background: linear-gradient(90deg, rgba(26,26,26,0) 0%, #D4AF37 50%, rgba(26,26,26,0) 100%);
            margin: 20px 0;
            border: none;
        }

        .glow-border {
            border: 1px solid rgba(212, 175, 55, 0.3); /* Low opacity gold border */
        }

        /* BUTTONS */
        .btn-gold {
            background-color: #D4AF37;
            color: #050505 !important;
            border: 1px solid #D4AF37;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background-color: #F6E6B6; /* primary-light */
            color: #000000 !important;
        }

        /* RESPONSIVE */
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .content-padding { padding: 20px !important; }
            .header-text { font-size: 24px !important; }
        }
    </style>
</head>

<body class="body-bg" style="background-color: #0f0f0f; margin: 0; padding: 0;">
    
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0f0f0f;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                
                <table role="presentation" class="email-container" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #1a1a1a; border-radius: 4px; border: 1px solid #2a2a2a; overflow: hidden;">
                    
                    <tr>
                        <td height="4" style="background-color: #D4AF37; background: linear-gradient(90deg, #0f0f0f 0%, #D4AF37 50%, #0f0f0f 100%);"></td>
                    </tr>

                    <tr>
                        <td class="content-padding" align="center" style="padding: 50px 40px 30px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="border: 1px solid #D4AF37; padding: 4px 12px; border-radius: 50px;">
                                        <span style="font-family: 'Montserrat', sans-serif; font-size: 10px; text-transform: uppercase; letter-spacing: 3px; color: #D4AF37;">
                                            Private Access
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <h1 class="header-text" style="font-family: 'Cinzel', serif; font-size: 32px; color: #FFFFFF; letter-spacing: 2px; text-transform: uppercase; font-weight: 400;">
                                {{ config('app.name') }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 40px;">
                            <div class="gold-divider" style="height: 1px; width: 100%; background-color: #333;">
                                </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="content-padding" style="padding: 40px; color: #FFFFFF;">
                            
                            <div style="font-family: 'Montserrat', sans-serif; font-weight: 300; font-size: 15px; line-height: 1.8; color: #e5e5e5;">
                                
                                @if($campaign->type === 'product' && $campaign->product)
                                    @include('emails.templates.product_announcement', ['product' => $campaign->product])
                                
                                @elseif($campaign->type === 'sale' && $campaign->sale)
                                    <div style="border: 1px solid #D4AF37; background-color: #0f0f0f; padding: 20px;">
                                        @include('emails.templates.sale_announcement', ['sale' => $campaign->sale])
                                    </div>

                                @elseif($campaign->type === 'offer' && $campaign->offer)
                                    @include('emails.newsletter.templates.offer', ['offer' => $campaign->offer])

                                @else
                                    {!! $content !!}
                                @endif

                            </div>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 40px;">
                                <tr>
                                    <td align="center">
                                        <a href="#" style="background-color: #D4AF37; color: #050505; font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; text-decoration: none; padding: 16px 36px; display: inline-block; border-radius: 2px;">
                                            Enter The Vault
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #050505; padding: 40px; border-top: 1px solid #2a2a2a; text-align: center;">
                            <p style="font-family: 'Playfair Display', serif; font-style: italic; color: #D4AF37; font-size: 14px; margin-bottom: 20px;">
                                "Excellence is not an act, but a habit."
                            </p>

                            <p style="font-family: 'Montserrat', sans-serif; color: #9CA3AF; font-size: 11px; letter-spacing: 1px; line-height: 1.6; text-transform: uppercase;">
                                You are receiving this because you hold a key to {{ config('app.name') }}.
                                <br><br>
                                <a href="{{ $unsubscribeUrl }}" style="color: #666666; text-decoration: underline; transition: color 0.3s;">Revoke Access</a>
                            </p>
                            
                            <img src="{{ $trackingPixelUrl }}" width="1" height="1" style="display:none;" alt="">
                        </td>
                    </tr>
                </table>
                
                <div style="max-width: 500px; margin: 0 auto; height: 20px; background: radial-gradient(ellipse at center, rgba(212, 175, 55, 0.15) 0%, rgba(15, 15, 15, 0) 70%);"></div>

            </td>
        </tr>
    </table>

</body>
</html>