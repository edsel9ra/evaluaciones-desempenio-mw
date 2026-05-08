<?php

namespace App\Services;

use App\Models\Role;

class RoleService
{
    public function getAllRoles()
    {
        return Role::with('competencies')->get();
    }

    public function createRole(array $data): Role
    {
        $role = Role::create($data);

        if (isset($data['competencies'])) {
            $role->competencies()->sync($data['competencies']);
        }

        return $role;
    }

    public function updateRole(int $id, array $data): Role
    {
        $role = Role::findOrFail($id);
        $role->update($data);

        if (isset($data['competencies'])) {
            $role->competencies()->sync($data['competencies']);
        }

        return $role;
    }

    public function deleteRole(int $id): void
    {
        Role::findOrFail($id)->delete();
    }
}
