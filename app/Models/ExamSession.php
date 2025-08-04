<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSession extends Model
{
    /** @use HasFactory<\Database\Factories\SessionFactory> */
    use HasFactory;
    protected $table = 'examsessions';

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
