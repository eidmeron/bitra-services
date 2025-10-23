<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meddelande om din bokning</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">{{ site_name() }}</h1>
        <p style="color: #e0e7ff; margin-top: 10px; font-size: 16px;">Meddelande från administratören</p>
    </div>

    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hej {{ $booking->customer_name }},</p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            {!! nl2br(e($messageContent)) !!}
        </div>

        <div style="background: #e5e7eb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #374151;">Bokningsdetaljer:</h3>
            <p style="margin: 5px 0;"><strong>Bokningsnummer:</strong> #{{ $booking->booking_number }}</p>
            <p style="margin: 5px 0;"><strong>Tjänst:</strong> {{ $booking->service->name }}</p>
            <p style="margin: 5px 0;"><strong>Delsumma:</strong> {{ number_format($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0)), 0, ',', ' ') }} kr</p>
            @if($booking->tax_amount > 0)
                <p style="margin: 5px 0;"><strong>Moms ({{ number_format($booking->tax_rate ?? ($booking->service->tax_rate ?? 25), 2, ',', ' ') }}%):</strong> {{ number_format($booking->tax_amount, 0, ',', ' ') }} kr</p>
            @endif
            <p style="margin: 5px 0; font-weight: bold;"><strong>Totalt (inkl. moms):</strong> {{ number_format($booking->total_with_tax ?? $booking->final_price, 0, ',', ' ') }} kr</p>
            <p style="margin: 5px 0;"><strong>Status:</strong> 
                @if($booking->status == 'pending')
                    Väntande
                @elseif($booking->status == 'assigned')
                    Tilldelad
                @elseif($booking->status == 'in_progress')
                    Pågående
                @elseif($booking->status == 'completed')
                    Slutförd
                @else
                    Avbruten
                @endif
            </p>
            @if($booking->company)
                <p style="margin: 5px 0;"><strong>Företag:</strong> {{ $booking->company->company_name ?? $booking->company->user->name ?? 'N/A' }}</p>
            @endif
        </div>

        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Mvh,<br>
            <strong>{{ site_name() }}</strong>
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px; padding: 20px; color: #9ca3af; font-size: 12px;">
        <p>© {{ date('Y') }} {{ site_name() }}. Alla rättigheter förbehållna.</p>
    </div>
</body>
</html>

