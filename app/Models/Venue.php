<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'venue_type_id', 'description', 'lat', 'lng',
        'address_street', 'address_number', 'address_city',
        'status', 'contact_name', 'contact_phone', 'contact_email',
        'web_url', 'instagram_url', 'facebook_url', 'owner_id',
        'gallery'
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function venueType(): BelongsTo
    {
        return $this->belongsTo(VenueType::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
