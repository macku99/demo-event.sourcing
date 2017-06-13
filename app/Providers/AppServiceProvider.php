<?php

namespace App\Providers;

use App\Domain\BasketRepository;
use App\Repositories\BasketEventSourcingRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BasketRepository::class, BasketEventSourcingRepository::class);
    }
}
