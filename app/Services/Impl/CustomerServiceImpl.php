<?php

namespace App\Services\Impl;

use App\Models\Customer;
use App\Services\CustomerService;

class CustomerServiceImpl implements CustomerService
{
    public function saveCustomer(Customer $customer): void
    {

        $customer = new Customer([
            "id" => $customer->id,
            "name" => $customer->name,
            "province" => $customer->province,
            "provinceId" => $customer->provinceId,
            "regency" => $customer->regency,
            "regencyId" => $customer->regencyId,
            "district" => $customer->district,
            "districtId" => $customer->districtId
        ]);

        $customer->save();
    }

    public function getCustomers(): array
    {
        return Customer::query()->get()->toArray();
    }

    public function getCustomerId(string $id): ?Customer
    {
        return Customer::query()->find($id);
    }

    public function removeCustomer(string $id): void
    {
        $customer = Customer::query()->find($id);
        if ($customer != null) {
            $customer->delete();
        }
    }

    public function updateCustomer(Customer $customer, string $id): bool
    {
        $existingCustomer = Customer::query()->find($id);


        $existingCustomer->name = $customer->name;
        $existingCustomer->province = $customer->province;
        $existingCustomer->provinceId = $customer->provinceId;
        $existingCustomer->regency = $customer->regency;
        $existingCustomer->regencyId = $customer->regencyId;
        $existingCustomer->district = $customer->district;
        $existingCustomer->districtId = $customer->districtId;

        $existingCustomer->update();

        return true;
    }
}
