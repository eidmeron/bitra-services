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

        return view('public.form', compact('form'));
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

