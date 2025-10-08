<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormBuilderRequest;
use App\Models\Form;
use App\Models\Service;
use App\Services\FormBuilderService;
use App\Services\ShortcodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormBuilderController extends Controller
{
    public function __construct(
        private FormBuilderService $formBuilderService,
        private ShortcodeGeneratorService $shortcodeService
    ) {
    }

    public function index(Request $request): View
    {
        $forms = Form::with('service')
            ->when($request->search, function ($query, $search) {
                $query->where('form_name', 'like', "%{$search}%");
            })
            ->when($request->service_id, function ($query, $serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        $services = Service::all();

        return view('admin.forms.index', compact('forms', 'services'));
    }

    public function create(Request $request): View
    {
        $services = Service::where('status', 'active')->get();
        $serviceId = $request->service_id;

        return view('admin.forms.create', compact('services', 'serviceId'));
    }

    public function store(FormBuilderRequest $request): RedirectResponse
    {
        $form = $this->formBuilderService->createForm($request->validated());

        return redirect()->route('admin.forms.edit', $form)
            ->with('success', 'Formulär skapat framgångsrikt. Du kan nu lägga till fält.');
    }

    public function edit(Form $form): View
    {
        $form->load('service', 'fields');
        $services = Service::where('status', 'active')->get();

        return view('admin.forms.edit', compact('form', 'services'));
    }

    public function update(FormBuilderRequest $request, Form $form): RedirectResponse
    {
        $this->formBuilderService->updateForm($form, $request->validated());

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulär uppdaterat framgångsrikt.');
    }

    public function destroy(Form $form): RedirectResponse
    {
        $form->delete();

        return redirect()->route('admin.forms.index')
            ->with('success', 'Formulär raderat framgångsrikt.');
    }

    public function preview(Form $form): View
    {
        $form->load('service', 'fields');

        return view('admin.forms.preview', compact('form'));
    }

    public function shortcode(Form $form): View
    {
        $form->load('service');

        $shortcode = $this->shortcodeService->generate($form);
        $publicUrl = $this->shortcodeService->generatePublicUrl($form);
        $embedCode = $this->shortcodeService->generateEmbedCode($form);
        $iframeCode = $this->shortcodeService->generateIframeCode($form);

        return view('admin.forms.shortcode', compact('form', 'shortcode', 'publicUrl', 'embedCode', 'iframeCode'));
    }
}

