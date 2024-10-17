<?php

namespace App\Providers;

use App\Models\Security\Menu;
use App\Policies\MenuPolicy;
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
        Gate::define('view-menu', 'App\Policies\MenuPolicy@view');
        Gate::define('approve1-menu', 'App\Policies\MenuPolicy@approve1');
        Gate::define('approve2-menu', 'App\Policies\MenuPolicy@approve2');
    }
}
