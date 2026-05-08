<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'department',
    ];

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'role_competency');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'role_id');
    }
}
