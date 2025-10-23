<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

final class EmailSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'type',
        'user_id',
        'company_id',
        'preferences',
        'is_active',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
    ];

    protected $casts = [
        'preferences' => 'array',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (!$subscriber->unsubscribe_token) {
                $subscriber->unsubscribe_token = Str::random(32);
            }
            if (!$subscriber->subscribed_at) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    /**
     * Scope: Only active subscribers
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: By type
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Users
     */
    public function scopeUsers(Builder $query): Builder
    {
        return $query->where('type', 'user');
    }

    /**
     * Scope: Companies
     */
    public function scopeCompanies(Builder $query): Builder
    {
        return $query->where('type', 'company');
    }

    /**
     * Scope: Guests
     */
    public function scopeGuests(Builder $query): Builder
    {
        return $query->where('type', 'guest');
    }

    /**
     * Get the user that owns the subscriber
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that owns the subscriber
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Subscribe a user
     */
    public static function subscribeUser(User $user, array $preferences = []): self
    {
        return self::updateOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'type' => 'user',
                'user_id' => $user->id,
                'preferences' => $preferences,
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );
    }

    /**
     * Subscribe a company
     */
    public static function subscribeCompany(Company $company, array $preferences = []): self
    {
        return self::updateOrCreate(
            ['email' => $company->user->email],
            [
                'name' => $company->company_name,
                'type' => 'company',
                'company_id' => $company->id,
                'preferences' => $preferences,
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );
    }

    /**
     * Subscribe a guest
     */
    public static function subscribeGuest(string $email, string $name = null, array $preferences = []): self
    {
        return self::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'type' => 'guest',
                'preferences' => $preferences,
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    /**
     * Resubscribe
     */
    public function resubscribe(): void
    {
        $this->update([
            'is_active' => true,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ]);
    }

    /**
     * Get unsubscribe URL
     */
    public function getUnsubscribeUrl(): string
    {
        return route('email.unsubscribe', $this->unsubscribe_token);
    }
}