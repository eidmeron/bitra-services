<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Form;
use App\Services\BookingWorkflowService;
use App\Services\PriceCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingSubmissionController extends Controller
{
    public function __construct(
        private BookingWorkflowService $bookingWorkflow,
        private PriceCalculatorService $priceCalculator
    ) {
    }

    public function store(BookingRequest $request, string $token): RedirectResponse
    {
        try {
            $form = Form::where('public_token', $token)
                ->where('status', 'active')
                ->firstOrFail();

            $data = $request->validated();
            
            // Log the data for debugging
            \Log::info('Booking submission data', [
                'service_id' => $data['service_id'] ?? null,
                'form_id' => $data['form_id'] ?? null,
                'city_id' => $data['city_id'] ?? null,
                'booking_type' => $data['booking_type'] ?? null,
                'has_form_data' => !empty($data['form_data']),
                'form_data_count' => count($data['form_data'] ?? [])
            ]);

            // Create booking (no automatic account creation)
            $booking = $this->bookingWorkflow->createBooking($data);

            // Check if we should redirect
            if ($form->redirect_after_submit && $form->redirect_url) {
                return redirect($form->redirect_url)
                    ->with('success', 'Bokning skapad! Bokningsnummer: ' . $booking->booking_number);
            }

            return redirect()
                ->route('public.form.success', ['booking' => $booking->booking_number])
                ->with('success', 'Din bokning har skapats framgångsrikt!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so they're handled properly
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Booking submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'token' => $token
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Ett fel uppstod vid bokningen. Vänligen försök igen.')
                ->withInput();
        }
    }

    public function calculatePrice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'form_id' => 'nullable|exists:forms,id',
            'form_data' => 'nullable|array',
            'apply_rot' => 'nullable|boolean',
            'booking_type' => 'nullable|in:one_time,subscription',
            'subscription_frequency' => 'nullable|in:daily,weekly,biweekly,monthly',
            'loyalty_points_to_use' => 'nullable|numeric|min:0',
        ]);

        try {
            // Add user ID if authenticated
            if (auth()->check()) {
                $validated['user_id'] = auth()->id();
            }

            $pricing = $this->priceCalculator->calculate($validated);

            return response()->json($pricing);
        } catch (\Exception $e) {
            \Log::error('Price calculation failed', [
                'error' => $e->getMessage(),
                'request' => $validated
            ]);
            
            return response()->json(['error' => 'Prisberäkning misslyckades'], 500);
        }
    }
}

