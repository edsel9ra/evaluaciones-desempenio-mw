<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competency extends Model
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_competency');
    }
}
