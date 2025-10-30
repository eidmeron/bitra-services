<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bokningssammanfattning - {{ $booking->booking_number }}</title>
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
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section h2 {
            color: #1f2937;
            font-size: 16px;
            margin: 0 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
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
        
        .price-breakdown {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .price-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding-top: 10px;
            margin-top: 10px;
            border-top: 2px solid #3b82f6;
        }
        
        .tax-row {
            color: #dc2626;
        }
        
        .deduction-row {
            color: #059669;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-assigned { background: #dbeafe; color: #1e40af; }
        .status-in_progress { background: #e0e7ff; color: #3730a3; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .company-info {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .form-data {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
        }
        
        .form-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .form-item:last-child {
            border-bottom: none;
        }
        
        .form-label {
            font-weight: bold;
            width: 40%;
        }
        
        .form-value {
            width: 60%;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ site_name() }}</h1>
        <p>Bokningssammanfattning</p>
        <p>Genererad: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <!-- Booking Information -->
    <div class="section">
        <h2>📋 Bokningsinformation</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Bokningsnummer:</div>
                <div class="info-value">{{ $booking->booking_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Skapad:</div>
                <div class="info-value">{{ $booking->created_at->format('Y-m-d H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $booking->status }}">
                        @if($booking->status === 'pending')
                            ⏳ Väntande
                        @elseif($booking->status === 'assigned')
                            📋 Tilldelad
                        @elseif($booking->status === 'in_progress')
                            🔄 Pågående
                        @elseif($booking->status === 'completed')
                            ✅ Slutförd
                        @else
                            ❌ Avbruten
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Information -->
    <div class="section">
        <h2>🛠️ Tjänstinformation</h2>
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
                <div class="info-label">Bokningstyp:</div>
                <div class="info-value">
                    @if($booking->booking_type === 'one_time')
                        En gång
                    @else
                        Prenumeration ({{ getSubscriptionFrequencyLabel($booking->subscription_frequency) }})
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="section">
        <h2>👤 Kundinformation</h2>
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
                    <div class="info-label">Organisationsnummer:</div>
                    <div class="info-value">{{ $booking->org_number }}</div>
                </div>
            @endif
            @if($booking->customer_type === 'private' && $booking->personnummer)
                <div class="info-row">
                    <div class="info-label">Personnummer (ROT):</div>
                    <div class="info-value">{{ $booking->personnummer }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Company Information -->
    @if($booking->company)
        <div class="section">
            <h2>🏢 Tilldelat företag</h2>
            <div class="company-info">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Företagsnamn:</div>
                        <div class="info-value">{{ $booking->company->company_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">E-post:</div>
                        <div class="info-value">{{ $booking->company->user->email }}</div>
                    </div>
                    @if($booking->company->company_number)
                        <div class="info-row">
                            <div class="info-label">Telefon:</div>
                            <div class="info-value">{{ $booking->company->company_number }}</div>
                        </div>
                    @endif
                    @if($booking->company->company_org_number)
                        <div class="info-row">
                            <div class="info-label">Organisationsnummer:</div>
                            <div class="info-value">{{ $booking->company->company_org_number }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Form Data -->
    @if($booking->form_data && is_array($booking->form_data) && count($booking->form_data) > 0)
        <div class="section">
            <h2>📝 Bokningsdetaljer</h2>
            <div class="form-data">
                @foreach($booking->form_data as $key => $value)
                    <div class="form-item">
                        <div class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</div>
                        <div class="form-value">
                            @if(is_array($value))
                                {{ implode(', ', $value) }}
                            @else
                                {{ $value }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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
            
            @php
                $taxRate = $booking->tax_rate ?? $booking->service->tax_rate ?? 25;
                $totalWithVAT = $booking->final_price;
                $baseAmount = $totalWithVAT / (1 + ($taxRate / 100));
                $vatAmount = $totalWithVAT - $baseAmount;
            @endphp
            
            <div class="price-row">
                <span>Delsumma (exkl. moms):</span>
                <span>{{ number_format($baseAmount, 2, ',', ' ') }} kr</span>
            </div>

            <div class="price-row tax-row">
                <span>Moms ({{ number_format($taxRate, 2, ',', ' ') }}%):</span>
                <span>{{ number_format($vatAmount, 2, ',', ' ') }} kr</span>
            </div>
            
            <div class="price-row">
                <span>Totalt (inkl. moms):</span>
                <span>{{ number_format($totalWithVAT, 2, ',', ' ') }} kr</span>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="section">
        <h2>📅 Tidslinje</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Bokning skapad:</div>
                <div class="info-value">{{ $booking->created_at->format('Y-m-d H:i') }}</div>
            </div>
            @if($booking->assigned_at)
                <div class="info-row">
                    <div class="info-label">Tilldelad till företag:</div>
                    <div class="info-value">{{ $booking->assigned_at->format('Y-m-d H:i') }}</div>
                </div>
            @endif
            @if($booking->completed_at)
                <div class="info-row">
                    <div class="info-label">Slutförd:</div>
                    <div class="info-value">{{ $booking->completed_at->format('Y-m-d H:i') }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Customer Message -->
    @if($booking->customer_message)
        <div class="section">
            <h2>💬 Kundens meddelande</h2>
            <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px;">
                <p style="margin: 0; font-style: italic;">"{{ $booking->customer_message }}"</p>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>© {{ date('Y') }} {{ site_name() }}. Alla rättigheter förbehållna.</p>
        @if(setting('contact_email'))
            <p>Kontakt: {{ setting('contact_email') }}</p>
        @endif
        <p>Detta dokument genererades automatiskt den {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>

