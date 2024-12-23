<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Pagination\Paginator;
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
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        Gate::define('chamados_upload', function(User $user)
        {
            if($user->level =='admin' | $user->level=='1')
            {
                return true;
            }
            else return false;
        });
        Gate::define('invoice_upload', function(User $user)
        {
            if($user->level =='admin' | $user->level=='1')
            {
                return true;
            }
            else return false;
        });
        Gate::define('manage_users', function(User $user) {
            if($user->level =='admin' | $user->level=='1')
            {
                return true;
            }
            else return false;
        });
        Gate::define('manage_news', function(User $user) {
            if($user->level =='admin' | $user->level=='1')
            {
                return true;
            }
            else return false;
        });
    }
}
