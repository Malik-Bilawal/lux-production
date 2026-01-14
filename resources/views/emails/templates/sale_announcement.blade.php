<div style="background-color: #050505; border: 1px solid #D4AF37; padding: 40px 30px; text-align: center; position: relative; margin: 20px 0;">
    
    <div style="margin-bottom: 20px;">
        @if($sale->status === 'active')
            <span style="color: #D4AF37; border-bottom: 1px solid #D4AF37; padding-bottom: 3px; font-size: 10px; text-transform: uppercase; letter-spacing: 3px;">
                ● Live Event
            </span>
        @else
            <span style="color: #666; font-size: 10px; text-transform: uppercase; letter-spacing: 3px;">
                Event Ended
            </span>
        @endif
    </div>

    <h2 style="font-family: 'Cinzel', serif; font-size: 28px; color: #FFFFFF; margin: 0 0 15px 0; font-weight: 400;">
        {{ $sale->title }}
    </h2>

    @if($sale->discount)
        <div style="margin: 20px 0;">
            <span style="font-size: 60px; line-height: 1; color: transparent; -webkit-text-stroke: 1px #D4AF37; font-family: 'Cinzel', serif; display: block;">
                {{ $sale->discount }}%
            </span>
            <span style="color: #D4AF37; font-size: 12px; text-transform: uppercase; letter-spacing: 4px; display: block; margin-top: 5px;">
                Privilege Discount
            </span>
        </div>
    @endif

    <div style="color: #9CA3AF; font-size: 14px; line-height: 1.8; margin-bottom: 30px; font-family: 'Montserrat', sans-serif;">
        {!! $sale->description !!}
    </div>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto 30px auto; border: 1px solid #222;">
        <tr>
            <td style="padding: 10px 20px; border-right: 1px solid #222; text-align: center;">
                <span style="display: block; font-size: 10px; color: #666; text-transform: uppercase;">Opens</span>
                <span style="color: #fff; font-size: 12px;">{{ \Carbon\Carbon::parse($sale->start_time)->format('M d') }}</span>
            </td>
            <td style="padding: 10px 20px; text-align: center;">
                <span style="display: block; font-size: 10px; color: #666; text-transform: uppercase;">Closes</span>
                <span style="color: #fff; font-size: 12px;">{{ \Carbon\Carbon::parse($sale->end_time)->format('M d') }}</span>
            </td>
        </tr>
    </table>

    @if($sale->status === 'active')
        <a href="{{ route('user.welcome', $sale->id) }}" style="display: inline-block; border: 1px solid #D4AF37; color: #D4AF37; padding: 12px 30px; text-decoration: none; font-size: 11px; text-transform: uppercase; letter-spacing: 2px; transition: all 0.3s;">
            Enter The Sale
        </a>
    @else
        <span style="color: #666; font-size: 12px; font-style: italic;">
            Access to this event is now closed.
        </span>
    @endif

</div>