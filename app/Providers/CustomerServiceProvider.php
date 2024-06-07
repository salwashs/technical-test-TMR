<?php

namespace App\Providers;

use App\Services\CustomerService;
use App\Services\Impl\CustomerServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public array $singletons = [
        CustomerService::class => CustomerServiceImpl::class
    ];

    public function provides(): array
    {
        return [CustomerService::class];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
