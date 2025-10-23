<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class BookingPdfController extends Controller
{
    /**
     * Generate PDF summary for a booking
     */
    public function generate(Booking $booking): Response
    {
        $booking->load(['service', 'city', 'company.user', 'user']);
        
        $pdf = Pdf::loadView('pdf.booking-summary', compact('booking'));
        
        $filename = "bokning-{$booking->booking_number}.pdf";
        
        return $pdf->download($filename);
    }
    
    /**
     * Generate PDF summary for a booking (stream)
     */
    public function stream(Booking $booking): Response
    {
        $booking->load(['service', 'city', 'company.user', 'user']);
        
        $pdf = Pdf::loadView('pdf.booking-summary', compact('booking'));
        
        return $pdf->stream("bokning-{$booking->booking_number}.pdf");
    }
}

