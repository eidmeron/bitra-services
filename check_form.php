<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$form = App\Models\Form::with('fields')->find(1);

echo "Form: {$form->form_name}\n";
echo "Total fields: " . $form->fields->count() . "\n\n";

foreach($form->fields as $field) {
    echo "ID: {$field->id}\n";
    echo "Type: {$field->field_type}\n";
    echo "Label: {$field->field_label}\n";
    echo "Name: {$field->field_name}\n";
    echo "Options: " . json_encode($field->field_options) . "\n";
    echo "Pricing: " . json_encode($field->pricing_rules) . "\n";
    echo "---\n";
}
