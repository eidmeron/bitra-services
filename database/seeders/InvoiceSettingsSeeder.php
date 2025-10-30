<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InvoiceSetting;
use Illuminate\Database\Seeder;

class InvoiceSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoiceSetting::create([
            'company_name' => 'Bitra Services AB',
            'company_org_number' => '556123-4567',
            'company_address' => "Storgatan 1\n123 45 Stockholm\nSverige",
            'company_phone' => '+46 8 123 456 78',
            'company_email' => 'info@bitra.se',
            'bankgiro_number' => '123-4567',
            'invoice_prefix' => 'INV',
            'invoice_counter' => 0,
            'payment_due_days' => 30,
            'invoice_footer_text' => 'Tack för att du använder Bitra Services! Vi uppskattar ditt förtroende och ser fram emot att fortsätta samarbeta med dig.',
            'payment_instructions' => "Betala till vårt bankgiro eller via Swish.\n\nBankgiro: 123-4567\nSwish: 123 456 78 90\n\nAnge fakturanummer som referens vid betalning.",
            'auto_send_invoices' => true,
            'include_booking_details' => true,
        ]);
    }
}