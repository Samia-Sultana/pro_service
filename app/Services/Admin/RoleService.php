<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\RoleServiceInterface;
use App\Models\Role;

class RoleService implements RoleServiceInterface
{
    private Role $roleModel;
    public function __construct(Role $roleModel)
    {
        $this->roleModel = $roleModel;
    }
    public function index()
    {
        // $query  = $this->roleModel->with('permissions');
        $query  = $this->roleModel;
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->roleModel->query();
        $role = $query->create([
            'name' => $data['name'],
        ]);
        // $role->permissions()->attach($data['permissions']);
        return $role;

    }

    public function roleDetail($id)
    {
        $query  = $this->roleModel->query();
        $admin = $query->findOrFail($id);
        return $admin;

    }

    public function edit(array $data)
    {
        $query  = $this->roleModel->query();
        $role = $query->find($data['id']);
        $role->name = $data['name'];
        $role->save();
        // $role->permissions()->sync($data['permissions']);
        return $role;
    }

    public function destroy($id)
    {
        $query  = $this->roleModel->query();
        $role = $query->find($id);
        if ($role) {
            return $role->delete();
        }
        return false;
    }


}
