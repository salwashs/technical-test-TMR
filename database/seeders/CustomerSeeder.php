<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = new Customer();

        $customer->id = "7382";
        $customer->name = "salwa";
        $customer->province = "Sulawesi Selatan";
        $customer->provinceId = 73;
        $customer->regency = "Kota Makassar";
        $customer->regencyId = 7371;
        $customer->district = "MAKASSAR";
        $customer->districtId = 7371040;

        $customer->save();
    }
}
