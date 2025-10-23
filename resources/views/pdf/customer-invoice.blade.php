<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktura - {{ $booking->booking_number }}</title>
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
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .invoice-left, .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .invoice-right {
            text-align: right;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section h2 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #3b82f6;
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
        
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .services-table th,
        .services-table td {
            border: 1px solid #d1d5db;
            padding: 12px;
            text-align: left;
        }
        
        .services-table th {
            background: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
        
        .services-table .text-right {
            text-align: right;
        }
        
        .price-breakdown {
            background: #f9fafb;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .price-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
            padding-top: 15px;
            margin-top: 15px;
            border-top: 3px solid #3b82f6;
            color: #1f2937;
        }
        
        .tax-row {
            color: #dc2626;
            font-weight: bold;
        }
        
        .deduction-row {
            color: #059669;
            font-weight: bold;
        }
        
        .total-row {
            background: #3b82f6;
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
        
        .payment-info {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        
        .payment-info h3 {
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
        
        .invoice-number {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        
        .invoice-date {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ site_name() }}</h1>
        <p>Faktura</p>
        <div class="invoice-number">Faktura #{{ $booking->booking_number }}</div>
        <div class="invoice-date">Fakturadatum: {{ now()->format('Y-m-d') }}</div>
    </div>

    <div class="invoice-info">
        <div class="invoice-left">
            <div class="section">
                <h2>🏢 Leverantör</h2>
                <div class="company-info">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Företag:</div>
                            <div class="info-value">{{ site_name() }}</div>
                        </div>
                        @if(setting('company_org_number'))
                            <div class="info-row">
                                <div class="info-label">Org.nr:</div>
                                <div class="info-value">{{ setting('company_org_number') }}</div>
                            </div>
                        @endif
                        @if(setting('contact_email'))
                            <div class="info-row">
                                <div class="info-label">E-post:</div>
                                <div class="info-value">{{ setting('contact_email') }}</div>
                            </div>
                        @endif
                        @if(setting('contact_phone'))
                            <div class="info-row">
                                <div class="info-label">Telefon:</div>
                                <div class="info-value">{{ setting('contact_phone') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="invoice-right">
            <div class="section">
                <h2>👤 Faktureras till</h2>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Namn:</div>
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
                    @if($booking->customer_type === 'company' && $booking->org_number)
                        <div class="info-row">
                            <div class="info-label">Org.nr:</div>
                            <div class="info-value">{{ $booking->org_number }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Service Information -->
    <div class="section">
        <h2>🛠️ Levererade tjänster</h2>
        <table class="services-table">
            <thead>
                <tr>
                    <th>Beskrivning</th>
                    <th>Kategori</th>
                    <th>Stad</th>
                    <th class="text-right">Enhetspris</th>
                    <th class="text-right">Antal</th>
                    <th class="text-right">Totalt</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $booking->service->name }}</td>
                    <td>{{ $booking->service->category->name }}</td>
                    <td>{{ $booking->city->name }}</td>
                    <td class="text-right">{{ number_format($booking->base_price, 2, ',', ' ') }} kr</td>
                    <td class="text-right">1</td>
                    <td class="text-right">{{ number_format($booking->base_price, 2, ',', ' ') }} kr</td>
                </tr>
                @if($booking->variable_additions > 0)
                    <tr>
                        <td colspan="5">Tillägg från formulär</td>
                        <td class="text-right">{{ number_format($booking->variable_additions, 2, ',', ' ') }} kr</td>
                    </tr>
                @endif
                @if($booking->subscription_multiplier && $booking->subscription_multiplier != 1.00)
                    <tr>
                        <td colspan="5">Prenumerationsmultiplikator (×{{ $booking->subscription_multiplier }})</td>
                        <td class="text-right">{{ number_format(($booking->base_price + $booking->variable_additions) * ($booking->subscription_multiplier - 1), 2, ',', ' ') }} kr</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5">Stadsmultiplikator ({{ $booking->city->name }} ×{{ $booking->city_multiplier }})</td>
                    <td class="text-right">{{ number_format(($booking->base_price + $booking->variable_additions) * ($booking->city_multiplier - 1), 2, ',', ' ') }} kr</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Price Breakdown -->
    <div class="section">
        <h2>💰 Prisberäkning</h2>
        <div class="price-breakdown">
            <div class="price-row">
                <span>Grundpris:</span>
                <span>{{ number_format($booking->base_price, 2, ',', ' ') }} kr</span>
            </div>
            
            @if($booking->variable_additions > 0)
                <div class="price-row">
                    <span>Tillägg från formulär:</span>
                    <span>+{{ number_format($booking->variable_additions, 2, ',', ' ') }} kr</span>
                </div>
            @endif

            @if($booking->subscription_multiplier && $booking->subscription_multiplier != 1.00)
                <div class="price-row">
                    <span>Prenumerationsmultiplikator:</span>
                    <span>×{{ $booking->subscription_multiplier }}</span>
                </div>
            @endif
            
            <div class="price-row">
                <span>Stadsmultiplikator ({{ $booking->city->name }}):</span>
                <span>×{{ $booking->city_multiplier }}</span>
            </div>
            
            @if($booking->discount_amount > 0)
                <div class="price-row deduction-row">
                    <span>🎁 Tjänsterabatt:</span>
                    <span>-{{ number_format($booking->discount_amount, 2, ',', ' ') }} kr</span>
                </div>
            @endif
            
            @if($booking->rot_deduction > 0)
                <div class="price-row deduction-row">
                    <span>💚 ROT-avdrag:</span>
                    <span>-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                </div>
            @endif
            
            <div class="price-row">
                <span>Delsumma:</span>
                <span>{{ number_format($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0)), 2, ',', ' ') }} kr</span>
            </div>

            <div class="price-row tax-row">
                <span>Moms ({{ number_format($booking->tax_rate ?? ($booking->service->tax_rate ?? 25), 2, ',', ' ') }}%):</span>
                <span>+{{ number_format($booking->tax_amount ?? 0, 2, ',', ' ') }} kr</span>
            </div>
            
            <div class="price-row">
                <span>Totalt att betala:</span>
                <span>{{ number_format($booking->total_with_tax ?? $booking->final_price, 2, ',', ' ') }} kr</span>
            </div>
        </div>
        
        <div class="total-row">
            ATT BETALA: {{ number_format($booking->total_with_tax ?? $booking->final_price, 2, ',', ' ') }} kr
        </div>
    </div>

    <!-- Payment Information -->
    <div class="payment-info">
        <h3>💳 Betalningsinformation</h3>
        <p><strong>Betalningsstatus:</strong> Betald via plattformen</p>
        <p><strong>Betalningsdatum:</strong> {{ $booking->created_at->format('Y-m-d') }}</p>
        <p><strong>Betalningsmetod:</strong> Online betalning</p>
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
                <div class="info-label">Utförandedatum:</div>
                <div class="info-value">{{ $booking->completed_at ? $booking->completed_at->format('Y-m-d') : 'Ej slutförd' }}</div>
            </div>
            @if($booking->company)
                <div class="info-row">
                    <div class="info-label">Utfört av:</div>
                    <div class="info-value">{{ $booking->company->company_name ?? $booking->company->user->email }}</div>
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p><strong>Viktig information:</strong></p>
        <p>Denna faktura är genererad automatiskt efter slutförd tjänst.</p>
        <p>För frågor om denna faktura, kontakta oss på {{ setting('contact_email') ?? 'support@bitratjanster.se' }}</p>
        <p>© {{ date('Y') }} {{ site_name() }}. Alla rättigheter förbehållna.</p>
        <p>Detta dokument genererades automatiskt den {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>

