<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationalCenter extends Model
{
    /** @use HasFactory<\Database\Factories\EducationalCenterFactory> */
    use HasFactory;

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
