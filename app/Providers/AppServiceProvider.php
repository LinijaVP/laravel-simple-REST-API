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
        //
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
