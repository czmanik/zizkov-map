<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'name', 'description'];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function programSlots(): HasMany
    {
        return $this->hasMany(ProgramSlot::class);
    }
}
