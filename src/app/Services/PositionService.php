<?php

namespace App\Services;

use App\Models\Position;

class PositionService
{
    public function getAllPositions()
    {
        return Position::with('indicators')->get();
    }

    public function createPosition(array $data): Position
    {
        $position = Position::create($data);
        
        if (isset($data['indicators'])) {
            $position->indicators()->sync($data['indicators']);
        }
        
        return $position;
    }

    public function updatePosition(int $id, array $data): Position
    {
        $position = Position::findOrFail($id);
        $position->update($data);
        
        if (isset($data['indicators'])) {
            $position->indicators()->sync($data['indicators']);
        }
        
        return $position;
    }

    public function deletePosition(int $id): void
    {
        Position::findOrFail($id)->delete();
    }
}
