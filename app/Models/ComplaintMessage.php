<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ComplaintMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'sender_type',
        'sender_id',
        'message',
        'attachments',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function getSenderNameAttribute(): string
    {
        return match ($this->sender_type) {
            'customer' => $this->complaint->customer_name,
            'company' => $this->complaint->company->company_name ?? $this->complaint->company->user->email,
            'admin' => 'Administratör',
            default => 'Okänd',
        };
    }

    public function getSenderAvatarAttribute(): string
    {
        return match ($this->sender_type) {
            'customer' => '👤',
            'company' => '🏢',
            'admin' => '👨‍💼',
            default => '❓',
        };
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}