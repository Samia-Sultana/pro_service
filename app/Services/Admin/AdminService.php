<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\AdminServiceInterface;
use App\Models\User;

class AdminService implements AdminServiceInterface
{
    private User $userModel;
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    public function index()
    {
        $query  = $this->userModel->query();
        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->userModel->query();
        $admin = $query->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return $admin;

    }

    public function adminDetail($id)
    {

    }

    public function edit(array $data)
    {

    }

    public function destroy($id)
    {

    }


}
