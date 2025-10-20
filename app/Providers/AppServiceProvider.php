<?php

namespace App\Providers;

use App\Repositories\Eloquent\AddressRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interface\AddressRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        AddressRepositoryInterface::class => AddressRepository::class,
    ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}