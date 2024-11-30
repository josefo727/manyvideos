<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    /** @use HasFactory<VideoFactory> */
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'path',
        'size',
        'duration',
        'resolution',
        'thumbnail',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'comments_count',
        'thumbnail_path',
        'size_formated',
        'formatted_duration',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_video');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return Attribute
     */
    protected function commentsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->comments()->count(),
        );
    }

    /**
     * @return Attribute
     */
    protected function thumbnailPath(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk('public')->url($this->thumbnail),
        );
    }

    protected function sizeFormated(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->formatBytes($this->size)
        );
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes > 0) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $power = floor(log($bytes, 1024));
            $value = $bytes / (1024 ** $power);
            return number_format($value, $precision) . ' ' . $units[$power];
        }
        return '0 B';
    }

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->formatDuration($this->duration)
        );
    }

    protected function formatDuration(int $seconds): string
    {
        $interval = CarbonInterval::seconds($seconds);

        return $interval->format('%i:%s');
    }
}
