<?php

namespace App\Services;

use App\Models\Competency;
use App\Models\Indicator;
use App\Models\Role;
use App\Models\Position;

class ItemService
{
    public function getAllCompetencies()
    {
        return Competency::with('roles')->get();
    }

    public function getAllIndicators()
    {
        return Indicator::with('positions')->get();
    }

    public function createCompetency(array $data): Competency
    {
        return Competency::create($data);
    }

    public function updateCompetency(int $id, array $data): Competency
    {
        $competency = Competency::findOrFail($id);
        $competency->update($data);
        return $competency;
    }

    public function deleteCompetency(int $id): void
    {
        Competency::findOrFail($id)->delete();
    }

    public function createIndicator(array $data): Indicator
    {
        return Indicator::create($data);
    }

    public function updateIndicator(int $id, array $data): Indicator
    {
        $indicator = Indicator::findOrFail($id);
        $indicator->update($data);
        return $indicator;
    }

    public function deleteIndicator(int $id): void
    {
        Indicator::findOrFail($id)->delete();
    }

    public function attachRoleCompetency(int $roleId, int $competencyId): void
    {
        $role = Role::findOrFail($roleId);
        $role->competencies()->syncWithoutDetaching([$competencyId]);
    }

    public function detachRoleCompetency(int $roleId, int $competencyId): void
    {
        $role = Role::findOrFail($roleId);
        $role->competencies()->detach($competencyId);
    }

    public function attachPositionIndicator(int $positionId, int $indicatorId): void
    {
        $position = Position::findOrFail($positionId);
        $position->indicators()->syncWithoutDetaching([$indicatorId]);
    }

    public function detachPositionIndicator(int $positionId, int $indicatorId): void
    {
        $position = Position::findOrFail($positionId);
        $position->indicators()->detach($indicatorId);
    }
}
