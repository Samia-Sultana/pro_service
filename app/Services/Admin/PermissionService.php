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
        $query  = $this->permissionModel->query();
        return $query->paginate(10);
    }



    public function edit(array $data)
    {

    }

    public function destroy($id)
    {
        $query  = $this->permissionModel->query();
        $admin = $query->find($id);
        if ($admin) {
            return $admin->delete();
        }
        return false;
    }


}
