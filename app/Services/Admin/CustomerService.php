<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\CustomerServiceInterface;
use App\Models\Customer;

class CustomerService implements CustomerServiceInterface
{
    private Customer $customerModel;
    public function __construct(Customer $customerModel)
    {
        $this->customerModel = $customerModel;
    }
    public function index($search = null)
    {
        $query  = $this->customerModel->query();

        if (!empty($search)) {
            foreach ($search as $field => $value) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        return $query->paginate(10);
    }
    public function store(array $data)
    {
        $query  = $this->customerModel->query();
        $customer = $query->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return $customer;

    }

    public function customerDetail($id)
    {
        $customer = $this->customerModel->with('wallet')->findOrFail($id);
        return $customer;

    }

    public function customerOrders($id)
    {
        $query  = $this->customerModel->query();
        $customer = $query->findOrFail($id);
        return $customer->orders;


    }

    public function edit(array $data)
    {

    }

    public function destroy($id)
    {
        $query  = $this->customerModel->query();
        $customer = $query->find($id);
        if ($customer) {
            return $customer->delete();
        }
        return false;
    }


}
