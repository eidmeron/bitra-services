<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create form for Hemstädning
        $hemstadning = Service::where('slug', 'hemstadning')->first();
        if ($hemstadning) {
            $form = Form::create([
                'service_id' => $hemstadning->id,
                'form_name' => 'Hemstädning - Bokningsformulär',
                'form_slug' => 'hemstadning-bokning',
                'form_schema' => [],
                'success_message' => 'Tack för din bokning! Vi återkommer inom 24 timmar med bekräftelse.',
                'redirect_after_submit' => false,
                'status' => 'active',
            ]);

            // Add fields
            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'number',
                'field_label' => 'Antal rum',
                'field_name' => 'antal_rum',
                'placeholder_text' => 'Ange antal rum',
                'help_text' => 'Hur många rum ska städas?',
                'field_width' => '50',
                'required' => true,
                'sort_order' => 1,
                'pricing_rules' => json_encode(['price_per_unit' => 100]),
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'number',
                'field_label' => 'Bostadsyta (kvm)',
                'field_name' => 'bostadsyta',
                'placeholder_text' => 'Ange yta i kvadratmeter',
                'help_text' => 'Total bostadsyta',
                'field_width' => '50',
                'required' => true,
                'sort_order' => 2,
                'pricing_rules' => json_encode(['price_per_unit' => 5]),
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'select',
                'field_label' => 'Typ av städning',
                'field_name' => 'typ_av_stadning',
                'placeholder_text' => '',
                'help_text' => 'Välj vilken typ av städning du behöver',
                'field_width' => '100',
                'required' => true,
                'sort_order' => 3,
                'field_options' => json_encode([
                    ['value' => 'grundstadning', 'label' => 'Grundstädning'],
                    ['value' => 'toppstadning', 'label' => 'Toppstädning'],
                    ['value' => 'storstadning', 'label' => 'Storstädning'],
                ]),
                'pricing_rules' => json_encode([
                    'options' => [
                        ['value' => 'grundstadning', 'price' => 0],
                        ['value' => 'toppstadning', 'price' => 200],
                        ['value' => 'storstadning', 'price' => 500],
                    ]
                ]),
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'checkbox',
                'field_label' => 'Tilläggstjänster',
                'field_name' => 'tillaggtjanster',
                'placeholder_text' => '',
                'help_text' => 'Välj eventuella tilläggstjänster',
                'field_width' => '100',
                'required' => false,
                'sort_order' => 4,
                'field_options' => json_encode([
                    ['value' => 'fonsterputsning', 'label' => 'Fönsterputsning'],
                    ['value' => 'ugnsrengoring', 'label' => 'Ugnsrengöring'],
                    ['value' => 'balkong', 'label' => 'Balkong/uteplats'],
                ]),
                'pricing_rules' => json_encode([
                    'options' => [
                        ['value' => 'fonsterputsning', 'price' => 300],
                        ['value' => 'ugnsrengoring', 'price' => 150],
                        ['value' => 'balkong', 'price' => 200],
                    ]
                ]),
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'date',
                'field_label' => 'Önskat datum',
                'field_name' => 'onskat_datum',
                'placeholder_text' => '',
                'help_text' => 'När önskar du att städningen ska utföras?',
                'field_width' => '50',
                'required' => false,
                'sort_order' => 5,
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'textarea',
                'field_label' => 'Särskilda önskemål',
                'field_name' => 'onskeman',
                'placeholder_text' => 'Beskriv eventuella särskilda önskemål...',
                'help_text' => 'T.ex. områden som behöver extra uppmärksamhet',
                'field_width' => '100',
                'required' => false,
                'sort_order' => 6,
            ]);

            $this->command->info("Created form for {$hemstadning->name} with 6 fields");
        }

        // Create form for Gräsklippning
        $grasklippning = Service::where('slug', 'grasklippning')->first();
        if ($grasklippning) {
            $form = Form::create([
                'service_id' => $grasklippning->id,
                'form_name' => 'Gräsklippning - Bokningsformulär',
                'form_slug' => 'grasklippning-bokning',
                'form_schema' => [],
                'success_message' => 'Tack för din bokning! Din trädgård kommer snart att se fantastisk ut.',
                'redirect_after_submit' => false,
                'status' => 'active',
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'number',
                'field_label' => 'Gräsyta (kvm)',
                'field_name' => 'grasyta',
                'placeholder_text' => 'Ange area i kvadratmeter',
                'help_text' => 'Uppskattad gräsyta',
                'field_width' => '50',
                'required' => true,
                'sort_order' => 1,
                'pricing_rules' => json_encode(['price_per_unit' => 2]),
            ]);

            FormField::create([
                'form_id' => $form->id,
                'field_type' => 'select',
                'field_label' => 'Frekvens',
                'field_name' => 'frekvens',
                'placeholder_text' => '',
                'help_text' => 'Hur ofta behöver gräset klippas?',
                'field_width' => '50',
                'required' => true,
                'sort_order' => 2,
                'field_options' => json_encode([
                    ['value' => 'en_gang', 'label' => 'En gång'],
                    ['value' => 'varje_vecka', 'label' => 'Varje vecka'],
                    ['value' => 'varannan_vecka', 'label' => 'Varannan vecka'],
                ]),
            ]);

            $this->command->info("Created form for {$grasklippning->name} with 2 fields");
        }
    }
}

