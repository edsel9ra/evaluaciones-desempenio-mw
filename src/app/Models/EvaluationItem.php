<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'item_type',
        'item_id',
        'weight',
        'score',
        'comments',
    ];

    protected $casts = [
        'weight' => 'double',
        'score' => 'double',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function item()
    {
        if ($this->item_type === 'competency') {
            return $this->belongsTo(Competency::class, 'item_id');
        }
        return $this->belongsTo(Indicator::class, 'item_id');
    }
}
