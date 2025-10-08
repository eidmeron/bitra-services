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
        $form = Form::where('public_token', $token)
            ->where('status', 'active')
            ->firstOrFail();

        // Create booking
        $booking = $this->bookingWorkflow->createBooking($request->validated());

        // Check if we should redirect
        if ($form->redirect_after_submit && $form->redirect_url) {
            return redirect($form->redirect_url);
        }

        return redirect()->route('public.form.success', ['booking' => $booking->booking_number]);
    }

    public function calculatePrice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'form_data' => 'nullable|array',
            'apply_rot' => 'nullable|boolean',
        ]);

        try {
            $pricing = $this->priceCalculator->calculate($validated);

            return response()->json($pricing);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Prisberäkning misslyckades'], 500);
        }
    }
}

