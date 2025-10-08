<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'form_name',
        'form_slug',
        'form_schema',
        'success_message',
        'redirect_after_submit',
        'redirect_url',
        'custom_styles',
        'shortcode',
        'public_token',
        'status',
    ];

    protected $casts = [
        'form_schema' => 'array',
        'custom_styles' => 'array',
        'redirect_after_submit' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($form) {
            if (!$form->shortcode) {
                $form->shortcode = 'bitra_' . Str::random(12);
            }
            if (!$form->public_token) {
                $form->public_token = Str::random(32);
            }
            if (!$form->form_slug) {
                $form->form_slug = Str::slug($form->form_name);
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('public.form', ['token' => $this->public_token]);
    }

    public function getShortcodeTextAttribute(): string
    {
        return "[bitra_form id=\"{$this->shortcode}\"]";
    }
}
