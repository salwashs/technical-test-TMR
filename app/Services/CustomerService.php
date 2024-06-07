<?php

namespace App\Services;

use App\Models\Customer;

interface CustomerService
{
    public function saveCustomer(Customer $customer): void;

    public function getCustomers(): array;

    public function removeCustomer(string $id): void;

    public function getCustomerId(string $id): ?Customer;

    public function updateCustomer(Customer $customer, string $id): bool;
}
