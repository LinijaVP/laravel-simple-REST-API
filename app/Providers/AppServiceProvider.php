<?php

namespace App\Providers;

use App\Models\Wantlist;
use App\Policies\WantlistPolicy;
use App\Models\Customer;
use App\Policies\CustomerPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Declare policies
        Gate::policy(Wantlist::class, WantlistPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);

    }
}
