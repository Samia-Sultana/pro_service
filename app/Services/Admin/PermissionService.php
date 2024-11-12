<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\PermissionServiceInterface;
use App\Models\Permission;
use App\Models\User;

class PermissionService implements PermissionServiceInterface
{
    private Permission $permissionModel;
    public function __construct(Permission $permissionModel)
    {
        $this->permissionModel = $permissionModel;
    }
    public function index()
    {
        $permissions = $this->permissionModel
        ->select('name', 'action')
        ->get()
        ->groupBy('name')
        ->map(function ($group) {
            return [
                'name' => $group->first()->name,
                'read' => $group->contains('action', 'read'),
                'write' => $group->contains('action', 'write'),
                'create' => $group->contains('action', 'create'),
            ];
        })
        ->values();

    return $permissions;
    }



    public function edit(array $data)
    {

    }

    public function destroy($id)
    {
        $query  = $this->permissionModel->query();
        $permission = $query->find($id);
        if ($permission) {
            return $permission->delete();
        }
        return false;
    }


}
