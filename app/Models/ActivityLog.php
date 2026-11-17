<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
        'properties',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aktivitas pengguna secara otomatis.
     */
    public static function record(string $action, string $description, ?Model $subject = null, ?array $properties = null): ?self
    {
        try {
            return static::create([
                'user_id' => auth()->id(),
                'action' => strtoupper($action),
                'description' => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject?->getKey(),
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'properties' => $properties,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

