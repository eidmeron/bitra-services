<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statusuppdatering - Bokning #{{ $booking->booking_number }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">{{ site_name() }}</h1>
        <p style="color: #e0e7ff; margin-top: 10px; font-size: 16px;">Uppdatering av din bokning</p>
    </div>

    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hej {{ $booking->customer_name }},</p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3b82f6;">
            <h3 style="margin-top: 0; color: #1f2937;">Status för din bokning har uppdaterats!</h3>
            <p style="margin: 10px 0;">
                <span style="text-decoration: line-through; color: #9ca3af;">
                    @if($oldStatus == 'pending')
                        Väntande
                    @elseif($oldStatus == 'assigned')
                        Tilldelad
                    @elseif($oldStatus == 'in_progress')
                        Pågående
                    @elseif($oldStatus == 'completed')
                        Slutförd
                    @else
                        Avbruten
                    @endif
                </span>
                <strong style="font-size: 24px; margin: 0 15px;">→</strong>
                <strong style="color: #10b981; font-size: 18px;">
                    @if($newStatus == 'pending')
                        ⏳ Väntande
                    @elseif($newStatus == 'assigned')
                        📋 Tilldelad
                    @elseif($newStatus == 'in_progress')
                        🔄 Pågående
                    @elseif($newStatus == 'completed')
                        ✅ Slutförd
                    @else
                        ❌ Avbruten
                    @endif
                </strong>
            </p>
        </div>

        <div style="background: #e5e7eb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #374151;">Bokningsdetaljer:</h3>
            <p style="margin: 5px 0;"><strong>Bokningsnummer:</strong> #{{ $booking->booking_number }}</p>
            <p style="margin: 5px 0;"><strong>Tjänst:</strong> {{ $booking->service->name }}</p>
            <p style="margin: 5px 0;"><strong>Stad:</strong> {{ $booking->city->name }}</p>
            <p style="margin: 5px 0;"><strong>Delsumma:</strong> {{ number_format($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0)), 0, ',', ' ') }} kr</p>
            @if($booking->tax_amount > 0)
                <p style="margin: 5px 0;"><strong>Moms ({{ number_format($booking->tax_rate ?? ($booking->service->tax_rate ?? 25), 2, ',', ' ') }}%):</strong> {{ number_format($booking->tax_amount, 0, ',', ' ') }} kr</p>
            @endif
            @if($booking->rot_deduction > 0)
                <p style="margin: 5px 0; color: #10b981;"><strong>ROT-avdrag:</strong> -{{ number_format($booking->rot_deduction, 0, ',', ' ') }} kr</p>
            @endif
            <p style="margin: 5px 0; font-weight: bold; font-size: 16px;"><strong>Totalt (inkl. moms):</strong> {{ number_format($booking->total_with_tax ?? $booking->final_price, 0, ',', ' ') }} kr</p>
            @if($booking->company)
                <p style="margin: 5px 0;"><strong>Företag:</strong> {{ $booking->company->company_name ?? $booking->company->user->name ?? 'N/A' }}</p>
                @if($booking->company->user && $booking->company->user->phone)
                    <p style="margin: 5px 0;"><strong>Telefon:</strong> {{ $booking->company->user->phone }}</p>
                @endif
                @if($booking->company->user && $booking->company->user->email)
                    <p style="margin: 5px 0;"><strong>E-post:</strong> {{ $booking->company->user->email }}</p>
                @endif
            @endif
        </div>

        @if($newStatus == 'completed')
            <div style="background: #d1fae5; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
                <h3 style="margin-top: 0; color: #065f46;">🎉 Tack för att du använde våra tjänster!</h3>
                <p>Din bokning är nu slutförd. Vi hoppas att du är nöjd med resultatet!</p>
                <p style="margin-top: 15px;">
                    @if($booking->user_id)
                        {{-- Logged in user - redirect to user dashboard --}}
                        <a href="{{ route('user.bookings.show', $booking) }}" 
                           style="display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                            ⭐ Lämna en recension
                        </a>
                    @else
                        {{-- Guest user - redirect to guest review page --}}
                        <a href="{{ route('public.booking.review.show', $booking->public_token) }}" 
                           style="display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                            ⭐ Lämna en recension
                        </a>
                    @endif
                </p>
            </div>
        @elseif($newStatus == 'cancelled')
            <div style="background: #fee2e2; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                <h3 style="margin-top: 0; color: #991b1b;">Bokning avbruten</h3>
                <p>Din bokning har avbrutits. Om du har några frågor, kontakta oss gärna.</p>
            </div>
        @endif

        <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
            Om du har några frågor, tveka inte att kontakta oss!<br><br>
            Mvh,<br>
            <strong>{{ site_name() }}</strong>
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px; padding: 20px; color: #9ca3af; font-size: 12px;">
        <p>© {{ date('Y') }} {{ site_name() }}. Alla rättigheter förbehållna.</p>
        @if(setting('contact_email'))
            <p>Kontakt: <a href="mailto:{{ setting('contact_email') }}" style="color: #3b82f6;">{{ setting('contact_email') }}</a></p>
        @endif
    </div>
</body>
</html>

