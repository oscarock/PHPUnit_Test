<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\ResourceRepository;
use App\Repositories\ReservationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ResourceRepositoryInterface::class, ResourceRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->singleton(ReservationFactory::class, function ($app) {
            return new ReservationFactory(
                $app->make(ResourceRepositoryInterface::class),
                $app->make(ReservationRepositoryInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
