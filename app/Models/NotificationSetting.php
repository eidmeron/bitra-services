<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'event',
        'subject',
        'template',
        'variables',
        'enabled',
        'auto_send',
        'recipients',
        'conditions',
        'priority',
    ];

    protected $casts = [
        'variables' => 'array',
        'enabled' => 'boolean',
        'auto_send' => 'boolean',
        'recipients' => 'array',
        'conditions' => 'array',
    ];

    // Scopes
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    public function scopeAutoSend($query)
    {
        return $query->where('auto_send', true);
    }

    // Get template with variables replaced
    public function getProcessedTemplate($variables = [])
    {
        $template = $this->template;
        
        foreach ($variables as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        
        return $template;
    }

    // Get processed subject with variables replaced
    public function getProcessedSubject($variables = [])
    {
        $subject = $this->subject ?? '';
        
        foreach ($variables as $key => $value) {
            $subject = str_replace("{{$key}}", $value, $subject);
        }
        
        return $subject;
    }
}
