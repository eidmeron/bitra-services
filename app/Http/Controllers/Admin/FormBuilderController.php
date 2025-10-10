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

    /**
     * Duplicate an existing form
     */
    public function duplicate(Form $form): RedirectResponse
    {
        $duplicatedForm = $this->formBuilderService->duplicateForm($form);

        return redirect()->route('admin.forms.edit', $duplicatedForm)
            ->with('success', 'Formulär duplicerat framgångsrikt.');
    }

    /**
     * Add a new field to the form via AJAX
     */
    public function addField(Request $request, Form $form)
    {
        $validated = $request->validate([
            'field_type' => 'required|string',
            'field_label' => 'required|string|max:255',
            'field_name' => 'required|string|max:255',
            'placeholder_text' => 'nullable|string',
            'help_text' => 'nullable|string',
            'field_width' => 'required|in:full,half,third,quarter',
            'required' => 'boolean',
            'field_options' => 'nullable|array',
            'pricing_rules' => 'nullable|array',
            'conditional_logic' => 'nullable|array',
            'validation_rules' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $field = $this->formBuilderService->addField($form, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Fält tillagt',
            'field' => $field,
        ]);
    }

    /**
     * Update an existing field
     */
    public function updateField(Request $request, Form $form, $fieldId)
    {
        $validated = $request->validate([
            'field_type' => 'sometimes|string',
            'field_label' => 'sometimes|string|max:255',
            'field_name' => 'sometimes|string|max:255',
            'placeholder_text' => 'nullable|string',
            'help_text' => 'nullable|string',
            'field_width' => 'sometimes|in:full,half,third,quarter',
            'required' => 'sometimes|boolean',
            'field_options' => 'nullable|array',
            'pricing_rules' => 'nullable|array',
            'conditional_logic' => 'nullable|array',
            'validation_rules' => 'nullable|array',
        ]);

        $field = $form->fields()->findOrFail($fieldId);
        $field->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Fält uppdaterat',
            'field' => $field,
        ]);
    }

    /**
     * Delete a field from the form
     */
    public function deleteField(Form $form, $fieldId)
    {
        $field = $form->fields()->findOrFail($fieldId);
        $field->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fält raderat',
        ]);
    }

    /**
     * Reorder fields
     */
    public function reorderFields(Request $request, Form $form)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:form_fields,id',
        ]);

        foreach ($validated['order'] as $index => $fieldId) {
            $form->fields()->where('id', $fieldId)->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fält omordnade',
        ]);
    }
}

