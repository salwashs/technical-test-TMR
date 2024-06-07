<?php

namespace Tests\Feature;

use App\Services\CustomerService;
use Database\Seeders\CustomerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    private CustomerService $customerService;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from customers");

        $this->seed(CustomerSeeder::class);
    }

    public function testHomePage()
    {
        $this->withSession(["user" => "user@mail"])->get('/beranda')->assertSeeText("Daftar Kustomer");
    }

    public function testFormCreatePage()
    {
        $this->withSession(["user" => "user@mail"])->get('/tambah-kustomer')
            ->assertSeeText("Tambah Kustomer")
            ->assertSeeText("Nama")
            ->assertSeeText("Provinsi")
            ->assertSeeText("Kabupaten");
    }

    public function testDoCreateSuccess()
    {
        $data = [
            "name" => "salwa",
            "province" => "11|Sulawesi Selatan",
            "regency" => "111|Gowa",
            "district" => "1111|Pallangga"
        ];

        $this->withSession(['user' => 'user@mail'])
            ->post('/tambah-kustomer', $data)
            ->assertRedirect("/beranda");
    }

    public function testDoCreateEmpty()
    {
        $data = [
            "name" => "",
            "province" => "",
            "regency" => "",
            "district" => ""
        ];

        $this->withSession(['user' => 'user@mail'])
            ->post('/tambah-kustomer', $data)
            ->assertSeeText("Form tidak boleh kosong");

        $data = [
            "name" => "salwa",
            "province" => "",
            "regency" => "",
            "district" => ""
        ];

        $this->withSession(['user' => 'user@mail'])
            ->post('/tambah-kustomer', $data)
            ->assertSeeText("Form tidak boleh kosong");

        $data = [
            "name" => "",
            "province" => "11|Sulawesi Selatan",
            "regency" => "111|Gowa",
            "district" => "1111|Pallangga"
        ];

        $this->withSession(['user' => 'user@mail'])
            ->post('/tambah-kustomer', $data)
            ->assertSeeText("Form tidak boleh kosong");
    }

    public function testDoRemove()
    {
        $this->withSession([
            "user" => "user@mail"
        ])->post("/kustomer/7382/delete")
            ->assertRedirect("/beranda");
    }

    public function testEditPage()
    {
        $this->withSession(['user' => 'user@mail'])
            ->get('/kustomer/7382/edit')
            ->assertSeeText("Edit Kustomer");
    }

    public function testDoUpdate()
    {
        $this->withSession(["user" => "user@mail"])
            ->post("/kustomer/7382/edit", [
                "name" => "salwa",
                "province" => "11|Sulawesi Selatan",
                "regency" => "111|Gowa",
                "district" => "1111|Pallangga"
            ])->assertRedirect("/beranda");
    }

    public function testDoUpdateEmpty()
    {
        $this->withSession(["user" => "user@mail"])
            ->post("/kustomer/7382/edit", [
                "name" => "",
                "province" => "",
                "regency" => "",
                "district" => ""
            ])->assertSeeText("Form tidak boleh kosong");
    }
}
