<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'field_type',
        'field_label',
        'field_name',
        'placeholder_text',
        'help_text',
        'field_width',
        'required',
        'sort_order',
        'pricing_rules',
        'conditional_logic',
        'field_options',
        'parent_id',
        'step_number',
    ];

    protected $casts = [
        'required' => 'boolean',
        'sort_order' => 'integer',
        'pricing_rules' => 'array',
        'conditional_logic' => 'array',
        'field_options' => 'array',
        'step_number' => 'integer',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function hasPricing(): bool
    {
        return !empty($this->pricing_rules);
    }
}
