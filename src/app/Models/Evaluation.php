<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'period',
        'status',
        'feedback',
        'total_score',
        'competency_score',
        'indicator_score',
        'evaluator_completed_date',
        'selected_competencies',
        'selected_indicators',
    ];

    protected $casts = [
        'total_score' => 'double',
        'competency_score' => 'double',
        'indicator_score' => 'double',
        'evaluator_completed_date' => 'date',
        'selected_competencies' => 'array',
        'selected_indicators' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(EvaluationItem::class, 'evaluation_id');
    }

    public function calculateTotalScore(): float
    {
        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($this->items as $item) {
            $totalWeightedScore += ($item->score ?? 0) * $item->weight;
            $totalWeight += $item->weight;
        }

        return $totalWeight > 0 ? round($totalWeightedScore, 2) : 0;
    }
}
