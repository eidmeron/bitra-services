<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PublicFormController extends Controller
{
    public function show(string $token): View
    {
        $form = Form::where('public_token', $token)
            ->where('status', 'active')
            ->with(['service', 'service.cities', 'fields'])
            ->firstOrFail();

        // Get all active cities with their zones
        $cities = \App\Models\City::where('status', 'active')
            ->with('zone')
            ->orderBy('name')
            ->get();

        // Set default booking type and subscription frequency
        $defaultBookingType = $form->service->one_time_booking ? 'one_time' : 'subscription';
        $defaultSubscriptionFreq = !empty($form->service->subscription_types) 
            ? $form->service->subscription_types[0] 
            : 'weekly';

        // Get booking settings for company selection
        $bookingSettings = [
            'show_companies' => (bool) setting('booking_show_companies', true),
            'allow_company_selection' => (bool) setting('booking_allow_company_selection', true),
            'auto_assign' => (bool) setting('booking_auto_assign', false),
        ];

        return view('public.form', compact('form', 'cities', 'defaultBookingType', 'defaultSubscriptionFreq', 'bookingSettings'));
    }

    public function html(string $token): Response
    {
        $form = Form::where('public_token', $token)
            ->where('status', 'active')
            ->with(['service', 'service.cities', 'fields'])
            ->firstOrFail();

        $html = view('public.form-embed', compact('form'))->render();

        return response($html)->header('Content-Type', 'text/html');
    }
}

