<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'service_id',
        'category_id',
        'city_id',
        'zone_id',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'h1_title',
        'hero_text',
        'content',
        'features',
        'faq',
        'schema_markup',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'faq' => 'array',
        'schema_markup' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('page_type', $type);
    }

    public function scopeCityService($query, int $cityId, int $serviceId)
    {
        return $query->where('page_type', 'city_service')
            ->where('city_id', $cityId)
            ->where('service_id', $serviceId);
    }

    // Helper Methods
    public function getMetaTitleAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        // Auto-generate if not set
        return $this->generateMetaTitle();
    }

    public function getMetaDescriptionAttribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        // Auto-generate if not set
        return $this->generateMetaDescription();
    }

    private function generateMetaTitle(): string
    {
        $parts = [];

        if ($this->service) {
            $parts[] = $this->service->name;
        }

        if ($this->city) {
            $parts[] = $this->city->name;
        }

        if ($this->category) {
            $parts[] = $this->category->name;
        }

        if (empty($parts)) {
            return 'Bitra Tjänster - Sveriges bästa tjänsteplattform';
        }

        return implode(' ', $parts) . ' | Bitra Tjänster';
    }

    private function generateMetaDescription(): string
    {
        if ($this->page_type === 'city_service' && $this->city && $this->service) {
            return "Boka {$this->service->name} i {$this->city->name}. Jämför priser från verifierade företag. Snabb service, transparenta priser och kvalitetsgaranti.";
        }

        if ($this->page_type === 'city' && $this->city) {
            return "Hitta och boka professionella tjänster i {$this->city->name}. Sveriges största tjänsteplattform med verifierade företag och transparenta priser.";
        }

        if ($this->page_type === 'service' && $this->service) {
            return "Boka {$this->service->name} från verifierade företag. Jämför priser, läs recensioner och få din offert direkt. Kvalitetsgaranti och snabb service.";
        }

        return 'Hitta och boka professionella tjänster från verifierade företag över hela Sverige. Transparenta priser, snabb service och kvalitetsgaranti.';
    }

    public function generateSchemaMarkup(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'provider' => [
                '@type' => 'Organization',
                'name' => 'Bitra Tjänster',
                'url' => url('/'),
            ],
        ];

        if ($this->service) {
            $schema['name'] = $this->service->name;
            $schema['description'] = $this->service->description;
            
            if ($this->service->base_price) {
                $schema['offers'] = [
                    '@type' => 'Offer',
                    'priceCurrency' => 'SEK',
                    'price' => $this->service->base_price,
                ];
            }
        }

        if ($this->city) {
            $schema['areaServed'] = [
                '@type' => 'City',
                'name' => $this->city->name,
            ];
        }

        return $schema;
    }
}
