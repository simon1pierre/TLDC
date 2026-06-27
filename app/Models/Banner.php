<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Banner extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'content',
        'link',
        'background_color',
        'text_color',
        'is_active',
        'starts_at',
        'ends_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function translated(string $field, ?string $locale = null): ?string
    {
        return $this->translatedValue($field, $this->{$field});
    }

    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');
    }

    public static function getActive(): array
    {
        return Cache::remember('banners.active', 300, function () {
            return self::query()->active()->get()->all();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('banners.active');
    }

    protected static function booted(): void
    {
        static::saved(function () {
            static::clearCache();
        });
        static::deleted(function () {
            static::clearCache();
        });
        static::restored(function () {
            static::clearCache();
        });
    }
}
