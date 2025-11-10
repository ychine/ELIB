<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Resource extends Model
{
    protected $primaryKey = 'Resource_ID';

    protected $fillable = [
        'Resource_Name',
        'File_Path',
        'thumbnail_path',
        'Type',
        'publish_year',
        'publish_month',
        'publish_day',
        'Uploaded_By',
        'Description',
        'status',
        'views',
    ];

    // Add this line to make thumbnail_path visible in JSON
    protected $appends = ['average_rating', 'formatted_publish_date', 'authors', 'tags'];

    // === RELATIONSHIPS ===
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'Uploaded_By');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'resource_authors', 'resource_id', 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'resource_tags', 'resource_id', 'tag_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'resource_id', 'Resource_ID');
    }

    public function resourceViews(): HasMany
    {
        return $this->hasMany(ResourceView::class, 'resource_id', 'Resource_ID');
    }

    // === ACCESSORS (100% SAFE – NO ERRORS EVER) ===
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->relationLoaded('ratings')
                ? round($this->ratings->avg('rating') ?? 0, 1)
                : 0.0
        );
    }

    protected function formattedPublishDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->publish_year) return 'N/A';
                $date = $this->publish_year;
                if ($this->publish_month) {
                    $date .= '-' . str_pad($this->publish_month, 2, '0', STR_PAD_LEFT);
                    if ($this->publish_day) {
                        $date .= '-' . str_pad($this->publish_day, 2, '0', STR_PAD_LEFT);
                    }
                }
                return $date;
            }
        );
    }

    // SAFE: Use Laravel's internal method
    public function getAuthorsAttribute(): string
    {
        $authors = $this->getRelationValue('authors');
        return $authors && $authors->isNotEmpty()
            ? $authors->pluck('name')->implode(', ')
            : 'Unknown Author';
    }

    public function getTagsAttribute(): array
    {
        $tags = $this->getRelationValue('tags');
        return $tags ? $tags->toArray() : [];
    }

    public function getAverageRatingAttribute(): float
    {
        $ratings = $this->getRelationValue('ratings');
        return $ratings ? round($ratings->avg('rating') ?? 0, 1) : 0.0;
    }

    // Add this accessor for thumbnail_path to work with Storage facade
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path 
            ? asset('storage/' . $this->thumbnail_path) 
            : null;
    }

    // === SCOPES ===
    public function scopeFeatured($query)
    {
        return $query->where('Type', 'Featured')->where('status', 'Available');
    }

    public function scopeCommunityUploads($query)
    {
        return $query->where('Type', 'Community Uploads')->where('status', 'Available');
    }

    public function scopeLatestUploads($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopularThisMonth($query)
    {
        return $query->join('resource_views', 'resources.Resource_ID', '=', 'resource_views.resource_id')
            ->where('resource_views.viewed_at', '>=', now()->startOfMonth())
            ->select('resources.*')
            ->selectRaw('COUNT(resource_views.id) as month_views')
            ->groupBy('resources.Resource_ID')
            ->orderByDesc('month_views');
    }

    public function scopePopularThisYear($query)
    {
        return $query->join('resource_views', 'resources.Resource_ID', '=', 'resource_views.resource_id')
            ->where('resource_views.viewed_at', '>=', now()->startOfYear())
            ->select('resources.*')
            ->selectRaw('COUNT(resource_views.id) as year_views')
            ->groupBy('resources.Resource_ID')
            ->orderByDesc('year_views');
    }

    // === HELPERS ===
    public function incrementViews($userId = null): void
    {
        $this->increment('views');

        if ($userId) {
            ResourceView::updateOrCreate(
                ['resource_id' => $this->Resource_ID, 'user_id' => $userId],
                ['viewed_at' => now()]
            );
        }
    }
}