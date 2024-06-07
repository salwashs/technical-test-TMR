<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerService;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    private CustomerService $customerService;
    private Client $client;

    public function __construct(CustomerService $customerService, Client $client)
    {
        $this->customerService = $customerService;
        $this->client = $client;
    }

    public function home(): Response
    {
        $customers = $this->customerService->getCustomers();

        $response = $this->client->request('GET', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $province = json_decode($response->getBody(), true);

        return response()->view("customer.home", [
            "title" => "Beranda",
            "customers" => $customers,
            "provinces" => $province
        ]);
    }

    public function createCustomer(): Response
    {
        $response = $this->client->request('GET', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $province = json_decode($response->getBody(), true);

        return response()->view("customer.form-create", [
            "title" => "Beranda",
            "provinces" => $province
        ]);
    }

    public function doCreateCustomer(Request $request): Response|RedirectResponse
    {
        $response = $this->client->request('GET', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinceOption = json_decode($response->getBody(), true);

        $name = $request->input("name");
        $province = $request->input("province");
        $regency = $request->input("regency");
        $district = $request->input("district");

        if (empty($name) || empty($province) || empty($regency) || empty($district)) {
            return response()->view("customer.form-create", [
                "title" => "Beranda",
                "provinces" => $provinceOption,
                "error" => "Form tidak boleh kosong"
            ]);
        }

        $strProvince = explode("|", $province);
        $strRegency = explode("|", $regency);
        $strDistrict = explode("|", $district);

        $customer = new Customer([
            "id" => uniqid(),
            "name" => $name,
            "province" => $strProvince[1],
            "provinceId" => (int)$strProvince[0],
            "regency" => $strRegency[1],
            "regencyId" => (int)$strRegency[0],
            "district" => $strDistrict[1],
            "districtId" => (int)$strDistrict[0],
        ]);

        $this->customerService->saveCustomer($customer);

        return redirect("/beranda");
    }

    public function editCustomer(Request $request, string $userId): Response
    {
        $customer = $this->customerService->getCustomerId($userId);

        $responseProvince = $this->client->request('GET', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $province = json_decode($responseProvince->getBody(), true);

        $responseRegency = $this->client
            ->request('GET', "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/" . $customer->provinceId . ".json");
        $regency = json_decode($responseRegency->getBody(), true);

        $responseDistrict = $this->client
            ->request('GET', "https://www.emsifa.com/api-wilayah-indonesia/api/districts/" . $customer->regencyId .  ".json");
        $district = json_decode($responseDistrict->getBody(), true);

        return response()->view("customer.edit", [
            "title" => "Edit Kustomer",
            "customer" => $customer,
            "provinces" => $province,
            "regencies" => $regency,
            "districts" => $district
        ]);
    }

    public function doEditCustomer(Request $request, string $userId): Response|RedirectResponse
    {
        $customer = $this->customerService->getCustomerId($userId);

        $responseProvince = $this->client->request('GET', 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $province = json_decode($responseProvince->getBody(), true);

        $responseRegency = $this->client
            ->request('GET', "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/" . $customer->provinceId . ".json");
        $regency = json_decode($responseRegency->getBody(), true);

        $responseDistrict = $this->client
            ->request('GET', "https://www.emsifa.com/api-wilayah-indonesia/api/districts/" . $customer->regencyId .  ".json");
        $district = json_decode($responseDistrict->getBody(), true);

        $name = $request->input("name");
        $provinceInpt = $request->input("province");
        $regencyInpt = $request->input("regency");
        $districtInpt = $request->input("district");

        if (empty($name) || empty($provinceInpt) || empty($regencyInpt) || empty($districtInpt)) {
            return response()->view("customer.edit", [
                "title" => "Edit Kustomer",
                "customer" => $customer,
                "provinces" => $province,
                "regencies" => $regency,
                "districts" => $district,
                "error" => "Form tidak boleh kosong"
            ]);
        }

        $strProvince = explode("|", $provinceInpt); // [111, nama]
        $strRegency = explode("|", $regencyInpt);
        $strDistrict = explode("|", $districtInpt);

        $updatedCustomer = new Customer([
            "id" => $userId,
            "name" => $name,
            "province" => $strProvince[1],
            "provinceId" => (int)$strProvince[0],
            "regency" => $strRegency[1],
            "regencyId" => (int)$strRegency[0],
            "district" => $strDistrict[1],
            "districtId" => (int)$strDistrict[0],
        ]);

        $this->customerService->updateCustomer($updatedCustomer, $userId);

        return redirect("/beranda");
    }

    public function removeCustomer(Request $request, string $userId): RedirectResponse
    {
        $this->customerService->removeCustomer($userId);

        return redirect()->action([CustomerController::class, 'home']);
    }
}
