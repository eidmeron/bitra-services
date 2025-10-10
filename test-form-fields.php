<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FORM BUILDER TEST ===\n\n";

// Get form 1
$form = App\Models\Form::with('fields')->find(1);

echo "Form: {$form->form_name}\n";
echo "Status: {$form->status}\n";
echo "Fields in database: {$form->fields->count()}\n\n";

if ($form->fields->count() > 0) {
    echo "--- FIELDS ---\n";
    foreach ($form->fields as $field) {
        echo sprintf(
            "%d. %s (%s) - %s\n   Width: %s, Required: %s\n   Pricing: %s\n   Validation: %s\n\n",
            $field->id,
            $field->field_label,
            $field->field_name,
            $field->field_type,
            $field->field_width,
            $field->required ? 'Yes' : 'No',
            json_encode($field->pricing_rules),
            json_encode($field->validation_rules)
        );
    }
}

echo "--- FORM SCHEMA ---\n";
echo json_encode($form->form_schema, JSON_PRETTY_PRINT) . "\n\n";

echo "✅ If you see fields above, they are correctly saved!\n";
echo "📝 Go to: http://127.0.0.1:8002/admin/forms/1/edit\n";
echo "🔄 Press Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows) to hard refresh\n";
echo "👁️  You should see the fields in the preview panel\n";
