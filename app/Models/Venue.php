<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Venue extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'venue_type_id', 'description', 'lat', 'lng',
        'address_street', 'address_number', 'address_city',
        'status', 'contact_name', 'contact_phone', 'contact_email',
        'web_url', 'instagram_url', 'facebook_url', 'owner_id',
        'opening_hours'
    ];

    protected $casts = [
        'opening_hours' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($venue) {
            if (empty($venue->slug)) {
                $venue->slug = Str::slug($venue->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function venueType(): BelongsTo
    {
        return $this->belongsTo(VenueType::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function programSlots(): HasManyThrough
    {
        return $this->hasManyThrough(ProgramSlot::class, Stage::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 400, 300)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Contain, 800, 600)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->fit(Fit::Contain, 1600, 1200)
            ->nonQueued();
    }
}
