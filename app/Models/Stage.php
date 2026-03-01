<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Stage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['venue_id', 'name', 'description'];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function programSlots(): HasMany
    {
        return $this->hasMany(ProgramSlot::class);
    }

    public function registerMediaCollections(): void
    {
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
