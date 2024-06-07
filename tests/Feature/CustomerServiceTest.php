<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Services\CustomerService;
use Database\Seeders\CustomerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Assert;
use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    private CustomerService $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete('delete from customers');

        $this->customerService = $this->app->make(CustomerService::class);
    }

    public function testCustomerNotNull()
    {
        self::assertNotNull(CustomerService::class);
    }

    public function testSaveCustomerSuccess()
    {

        $customer = new Customer([
            "id" => uniqid(),
            "name" => "salwa",
            "province" => "Sulawesi Selatan",
            "provinceId" => 73,
            "regency" => "Kota Makassar",
            "regencyId" => 7371,
            "district" => "MARICAYA",
            "districtId" => 737104
        ]);

        $this->customerService->saveCustomer($customer);

        $customer = $this->customerService->getCustomers();

        foreach ($customer as $value) {
            self::assertEquals("salwa", $value['name']);
            self::assertEquals("Sulawesi Selatan", $value['province']);
            self::assertEquals(73, $value['provinceId']);
            self::assertEquals("Kota Makassar", $value['regency']);
            self::assertEquals(7371, $value['regencyId']);
            self::assertEquals("MARICAYA", $value['district']);
            self::assertEquals(737104, $value['districtId']);
        }
    }

    public function testGetCustomerEmpty()
    {
        self::assertEquals([], $this->customerService->getCustomers());
    }

    public function testGetCustomerNotEmpty()
    {
        $expected = [
            "id" => uniqid(),
            "name" => "salwa",
            "province" => "DIY Yogyakarta",
            "provinceId" => 73,
            "regency" => "Yogyakarta",
            "regencyId" => 7371,
            "district" => "Sleman",
            "districtId" => 737104
        ];

        $customer = new Customer($expected);

        $this->customerService->saveCustomer($customer);

        $actualCustomers = $this->customerService->getCustomers();

        $found = false;

        foreach ($actualCustomers as $customer) {

            if ($customer['id'] == $expected['id']) {
                $found = true;

                foreach ($expected as $key => $value) {
                    $this->assertEquals($value, $customer[$key]);
                }
            }
        }

        $this->assertTrue($found);
    }


    public function testRemoveCustomerById()
    {
        $this->seed(CustomerSeeder::class);

        $customer = $this->customerService->getCustomers();

        $this->customerService->removeCustomer("notfound");

        $customer = $this->customerService->getCustomers();

        self::assertSame(1, sizeof($customer));

        $this->customerService->removeCustomer("7382");

        $customer = $this->customerService->getCustomers();

        self::assertSame(0, sizeof($customer));
    }

    public function testGetCustomerById()
    {
        $this->seed(CustomerSeeder::class);

        $result = $this->customerService->getCustomerId("notfound");

        self::assertNull($result);

        $result = $this->customerService->getCustomerId("7382");

        self::assertSame("salwa", $result["name"]);
        self::assertSame("Sulawesi Selatan", $result["province"]);
        self::assertSame('73', $result["provinceId"]);
        self::assertSame("Kota Makassar", $result["regency"]);
        self::assertSame('7371', $result["regencyId"]);
        self::assertSame("MAKASSAR", $result["district"]);
        self::assertSame('7371040', $result["districtId"]);
    }


    public function testUpdateCustomerByNameSuccess()
    {
        $this->seed(CustomerSeeder::class);

        $updateCustomer = Customer::query()->where("name", "salwa")->update([
            "name" => "salsabila"
        ]);


        self::assertSame($updateCustomer, 1);
    }

    public function testUpdateCustomerByNameNotFound()
    {
        $this->seed(CustomerSeeder::class);

        $updateCustomer = Customer::query()->where("name", "salah")->update([
            "name" => "salsabila"
        ]);


        self::assertSame($updateCustomer, 0);
    }
}
