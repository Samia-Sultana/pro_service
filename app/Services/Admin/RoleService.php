<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\RoleServiceInterface;
use App\Models\Permission;
use App\Models\Role;

class RoleService implements RoleServiceInterface
{
    private Role $roleModel;
    public function __construct(Role $roleModel)
    {
        $this->roleModel = $roleModel;
    }
    public function index($search = null)
    {
        $query = $this->roleModel->with(['permissions' => function ($query) {
            $query->select('permissions.id', 'permissions.name')
                  ->join('permission_role as pr', 'permissions.id', '=', 'pr.permission_id')
                  ->where('pr.enabled', true);
        }]);

        // Search filter if provided
        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }

    public function getRolePermissions($roleId)
    {
        $role = $this->roleModel->with(['permissions' => function ($query) use ($roleId) {
            $query->select('permissions.id', 'permissions.name', 'action')
                  ->join('permission_role as pr', 'permissions.id', '=', 'pr.permission_id')
                  ->where('pr.role_id', $roleId)
                  ->where('pr.enabled', true);
        }])->find($roleId);

        if (!$role) {
            return []; // or handle not found
        }

        // Transform permissions to the desired format
        $permissions = $role->permissions->groupBy('name')->map(function ($group) {
            return [
                'name' => $group->first()->name,
                'read' => $group->contains('action', 'read'),
                'edit' => $group->contains('action', 'edit'),
                'delete' => $group->contains('action', 'delete'),
                'create' => $group->contains('action', 'create'),
                'send_money' => $group->contains('action', 'send_money'),
                'withdraw' => $group->contains('action', 'withdraw'),
            ];
        })->values()->toArray(); // Convert collection to array

        return $permissions;
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
    public function edit(array $data) {
        // Find the role by ID and update its name
        $role = $this->roleModel->findOrFail($data['id']);
        $role->name = $data['name'];
        $role->save();

        $permissionsData = [];
        foreach ($data['permissions'] as $permission) {
            if (!empty($permission['read'])) {
                $permissionRecord = Permission::where('name', $permission['name'])->where('action', 'read')->first();
                if ($permissionRecord) {
                    $permissionsData[$permissionRecord->id] = ['enabled' => 1];
                }
            }
            if (!empty($permission['create'])) {
                $permissionRecord = Permission::where('name', $permission['name'])->where('action', 'create')->first();
                if ($permissionRecord) {
                    $permissionsData[$permissionRecord->id] = ['enabled' => 1];
                }
            }
            if (!empty($permission['edit'])) {
                $permissionRecord = Permission::where('name', $permission['name'])->where('action', 'edit')->first();
                if ($permissionRecord) {
                    $permissionsData[$permissionRecord->id] = ['enabled' => 1];
                }
            }
            if (!empty($permission['delete'])) {
                $permissionRecord = Permission::where('name', $permission['name'])->where('action', 'delete')->first();
                if ($permissionRecord) {
                    $permissionsData[$permissionRecord->id] = ['enabled' => 1];
                }
            }

             if (!empty($permission['send_money'])) {
                $permissionRecord = Permission::where('name', $permission['name'])->where('action', 'send_money')->first();
                if ($permissionRecord) {
                    $permissionsData[$permissionRecord->id] = ['enabled' => 1];
                }
            }

            if (!empty($permission['withdraw'])) {
                $permissionRecord = Permission::where('name', $permission['name'])->where('action', 'withdraw')->first();
                if ($permissionRecord) {
                    $permissionsData[$permissionRecord->id] = ['enabled' => 1];
                }
            }
        }

        // Sync only permissions with `enabled` set to 1
        $role->permissions()->sync($permissionsData);

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
