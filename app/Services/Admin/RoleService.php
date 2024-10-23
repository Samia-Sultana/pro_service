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
        $query  = $this->roleModel->query();
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        info('lasdjsk');
        $query  = $this->roleModel->query();
        $role = $query->create([
            'name' => $data['name'],
        ]);
        $role->permissions()->attach($data['permissions']);
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

    }

    public function destroy($id)
    {
        $query  = $this->roleModel->query();
        $admin = $query->find($id);
        if ($admin) {
            return $admin->delete();
        }
        return false;
    }


}
