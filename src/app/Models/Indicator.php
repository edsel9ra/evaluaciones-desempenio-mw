<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'weight',
    ];

    protected $casts = [
        'weight' => 'double',
    ];

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'position_indicator');
    }
}
