<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbetsrapport - {{ $booking->booking_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #10b981;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section h2 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #10b981;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 15px 8px 0;
            width: 30%;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px 0;
            vertical-align: top;
        }
        
        .earnings-breakdown {
            background: #f0fdf4;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .earnings-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #d1fae5;
        }
        
        .earnings-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
            padding-top: 15px;
            margin-top: 15px;
            border-top: 3px solid #10b981;
            color: #1f2937;
        }
        
        .commission-row {
            color: #dc2626;
            font-weight: bold;
        }
        
        .rot-row {
            color: #059669;
            font-weight: bold;
        }
        
        .net-earnings {
            background: #10b981;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .payout-info {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .payout-info h3 {
            color: #92400e;
            margin: 0 0 10px 0;
        }
        
        .company-info {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .report-number {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }
        
        .report-date {
            font-size: 14px;
            color: #666;
        }
        
        .tax-note {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            font-size: 11px;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ site_name() }}</h1>
        <p>Arbetsrapport för företag</p>
        <div class="report-number">Rapport #{{ $booking->booking_number }}</div>
        <div class="report-date">Rapportdatum: {{ now()->format('Y-m-d') }}</div>
    </div>

    <!-- Company Information -->
    <div class="section">
        <h2>🏢 Företagsinformation</h2>
        <div class="company-info">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Företag:</div>
                    <div class="info-value">{{ $booking->company->company_name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">E-post:</div>
                    <div class="info-value">{{ $booking->company->user->email }}</div>
                </div>
                @if($booking->company->company_org_number)
                    <div class="info-row">
                        <div class="info-label">Org.nr:</div>
                        <div class="info-value">{{ $booking->company->company_org_number }}</div>
                    </div>
                @endif
                @if($booking->company->company_number)
                    <div class="info-row">
                        <div class="info-label">Telefon:</div>
                        <div class="info-value">{{ $booking->company->company_number }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Service Information -->
    <div class="section">
        <h2>🛠️ Utförd tjänst</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tjänst:</div>
                <div class="info-value">{{ $booking->service->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kategori:</div>
                <div class="info-value">{{ $booking->service->category->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Stad:</div>
                <div class="info-value">{{ $booking->city->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Utförandedatum:</div>
                <div class="info-value">{{ $booking->completed_at ? $booking->completed_at->format('Y-m-d H:i') : 'Ej slutförd' }}</div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="section">
        <h2>👤 Kundinformation</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Kund:</div>
                <div class="info-value">{{ $booking->customer_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">E-post:</div>
                <div class="info-value">{{ $booking->customer_email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Telefon:</div>
                <div class="info-value">{{ $booking->customer_phone }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kundtyp:</div>
                <div class="info-value">
                    @if($booking->customer_type === 'company')
                        🏢 Företag
                    @else
                        🏠 Privatperson
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Breakdown -->
    <div class="section">
        <h2>💰 Intäktsberäkning</h2>
        <div class="earnings-breakdown">
            <div class="earnings-row">
                <span>Kundens betalning (inkl. moms):</span>
                <span>{{ number_format($booking->total_with_tax ?? $booking->final_price, 2, ',', ' ') }} kr</span>
            </div>
            
            <div class="earnings-row">
                <span>Moms ({{ number_format($booking->tax_rate ?? ($booking->service->tax_rate ?? 25), 2, ',', ' ') }}%):</span>
                <span>-{{ number_format($booking->tax_amount ?? 0, 2, ',', ' ') }} kr</span>
            </div>
            
            <div class="earnings-row">
                <span>Delsumma (exkl. moms):</span>
                <span>{{ number_format($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0)), 2, ',', ' ') }} kr</span>
            </div>
            
            @if($booking->rot_deduction > 0)
                <div class="earnings-row rot-row">
                    <span>ROT-avdrag (kundens skattereduktion):</span>
                    <span>-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                </div>
            @endif
            
            @php
                $commissionSetting = $booking->company->commissionSetting;
                $commissionAmount = 0;
                if ($commissionSetting && $commissionSetting->is_active) {
                    $bookingAmount = (float) $booking->final_price;
                    $commissionAmount = $commissionSetting->calculateCommission($bookingAmount);
                }
            @endphp
            
            @if($commissionAmount > 0)
                <div class="earnings-row commission-row">
                    <span>Admin provision ({{ $commissionSetting->commission_type === 'percentage' ? $commissionSetting->percentage . '%' : 'fast belopp' }}):</span>
                    <span>-{{ number_format($commissionAmount, 2, ',', ' ') }} kr</span>
                </div>
            @endif
            
            <div class="earnings-row">
                <span>Din nettointäkt:</span>
                <span>{{ number_format(($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0))) - $commissionAmount, 2, ',', ' ') }} kr</span>
            </div>
        </div>
        
        <div class="net-earnings">
            NETTOINTÄKT: {{ number_format(($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0))) - $commissionAmount, 2, ',', ' ') }} kr
        </div>
    </div>

    <!-- Payout Information -->
    <div class="payout-info">
        <h3>💳 Utbetalningsinformation</h3>
        <p><strong>Utbetalningsstatus:</strong> Väntar på veckovis utbetalning</p>
        <p><strong>Nästa utbetalning:</strong> Varje måndag (för föregående veckas arbeten)</p>
        <p><strong>Utbetalningsmetod:</strong> Banköverföring till ditt registrerade konto</p>
        <p><strong>Bearbetningstid:</strong> 1-3 bankdagar efter godkännande</p>
    </div>

    <!-- Tax Information -->
    <div class="tax-note">
        <h4>📋 Viktig skatteinformation</h4>
        <p><strong>ROT-avdrag:</strong> ROT-avdraget är kundens skattereduktion och påverkar inte din intäkt. Kundens ROT-avdrag hanteras direkt med Skatteverket.</p>
        <p><strong>Din skatt:</strong> Du ansvarar för att deklarera din nettointäkt enligt gällande skatteregler.</p>
        <p><strong>Moms:</strong> Momsen på kundens betalning hanteras av {{ site_name() }} enligt svenska momsregler.</p>
    </div>

    <!-- Service Details -->
    <div class="section">
        <h2>📋 Tjänstedetaljer</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Bokningsnummer:</div>
                <div class="info-value">{{ $booking->booking_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Bokningsdatum:</div>
                <div class="info-value">{{ $booking->created_at->format('Y-m-d H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tilldelad:</div>
                <div class="info-value">{{ $booking->assigned_at ? $booking->assigned_at->format('Y-m-d H:i') : 'Ej tilldelad' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Slutförd:</div>
                <div class="info-value">{{ $booking->completed_at ? $booking->completed_at->format('Y-m-d H:i') : 'Ej slutförd' }}</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Viktig information:</strong></p>
        <p>Denna rapport genereras automatiskt efter slutförd tjänst.</p>
        <p>Utbetalningar sker veckovis varje måndag för föregående veckas arbeten.</p>
        <p>För frågor om utbetalningar, kontakta oss på {{ setting('contact_email') ?? 'support@bitratjanster.se' }}</p>
        <p>© {{ date('Y') }} {{ site_name() }}. Alla rättigheter förbehållna.</p>
        <p>Detta dokument genererades automatiskt den {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>

