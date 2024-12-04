<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'path',
        'size',
        'duration',
        'resolution',
        'thumbnail',
    ];

    protected $appends = [
        'comments_count',
        'thumbnail_path',
        'formatted_size',
        'formatted_duration',
    ];

    private const SIZE_UNITS = ['B', 'KB', 'MB', 'GB', 'TB'];
    private const DEFAULT_SIZE_PRECISION = 2;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    protected function commentsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->comments()->count(),
        );
    }

    protected function thumbnailPath(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getPublicThumbnailUrl(),
        );
    }

    private function getPublicThumbnailUrl(): string
    {
        return empty($this->thumbnail)
            ? '#'
            : Storage::disk('public')->url($this->thumbnail);
    }

    protected function formattedSize(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->formatBytes($this->size),
        );
    }

    private function formatBytes(?int $bytes, int $precision = self::DEFAULT_SIZE_PRECISION): string
    {
        if ($bytes > 0) {
            $power = floor(log($bytes, 1024));
            $value = $bytes / (1024 ** $power);
            $unit = self::SIZE_UNITS[$power];
            return number_format($value, $precision) . " $unit";
        }
        return '0 B';
    }

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->formatDuration($this->duration),
        );
    }

    private function formatDuration(?int $seconds): string
    {
        $seconds = $seconds ?? 0;
        $interval = CarbonInterval::seconds($seconds);
        return $interval->format('%i:%s');
    }
}
