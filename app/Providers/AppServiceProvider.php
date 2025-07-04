<?php

namespace App\Providers;
use App\Models\Country;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         View::composer('models.add_employee_modal', function ($view) {
        $view->with('countries', Country::all());
    });
    }
}
