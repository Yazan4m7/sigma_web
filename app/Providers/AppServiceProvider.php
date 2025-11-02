<?php

namespace App\Providers;

use App\Http\Controllers\OperationsUpgrade;
use App\job;
use App\Observers\JobObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // YSH Telescope  23.4.2025
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        class_exists(\App\Device::class);
        Paginator::useBootstrap();
       View::share('dashboardName', 'Operations Dashboard');
       View::share('viewCase', 'Case Profile');
       View::share('editCase', 'Edit Case');
        View::share('clientTitle', 'Doctor');
        View::share('voucher', 'Voucher');
        View::share('user', 'User');
        View::share('device', 'Machine');
        View::share('failureCause', 'Fail Cause');
        View::share('reject', 'Reject');
        View::share('modify', 'Modify');
        View::share('repeat', 'Repeat');
        Job::observe(JobObserver::class);
        View::composer('*', function ($view) {
            $view->with('stageConfig', OperationsUpgrade::STAGE_CONFIG);
        });
    }
}
