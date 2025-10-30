<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_org_number',
        'company_address',
        'company_phone',
        'company_email',
        'bankgiro_number',
        'invoice_prefix',
        'invoice_counter',
        'payment_due_days',
        'invoice_footer_text',
        'payment_instructions',
        'auto_send_invoices',
        'include_booking_details',
    ];

    protected $casts = [
        'invoice_counter' => 'integer',
        'payment_due_days' => 'integer',
        'auto_send_invoices' => 'boolean',
        'include_booking_details' => 'boolean',
    ];

    public static function getSettings(): self
    {
        return static::first() ?? static::create([
            'company_name' => 'Bitra Services AB',
            'company_org_number' => '556123-4567',
            'company_address' => 'Storgatan 1, 123 45 Stockholm',
            'company_phone' => '+46 8 123 456 78',
            'company_email' => 'info@bitra.se',
            'bankgiro_number' => '123-4567',
            'invoice_prefix' => 'INV',
            'invoice_counter' => 0,
            'payment_due_days' => 30,
            'invoice_footer_text' => 'Tack för att du använder Bitra Services!',
            'payment_instructions' => 'Betala till vårt bankgiro eller via Swish.',
            'auto_send_invoices' => true,
            'include_booking_details' => true,
        ]);
    }
}