<?php

namespace App\Providers;

use App\abutmentDeliveryRecord;
use App\Build;
use App\caseTag;
use App\Device;
use App\Http\Controllers\OperationsUpgrade;
use App\job;
use App\note;
use App\Observers\JobObserver;
use App\Observers\NoteObserver;
use App\Observers\OperationsDashboardObserver;
use App\sCase;
use App\Services\TableWidthPreferences;
use App\tag;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        class_exists(\App\device::class);
        Paginator::useBootstrap();
        View::share('dashboardName', 'Operations Dashboard');
        View::share('viewCase', 'Case Profile');
        View::share('editCase', 'Edit');
        View::share('clientTitle', 'Doctor');
        View::share('voucher', 'Voucher');
        View::share('user', 'User');
        View::share('device', 'Machine');
        View::share('failureCause', 'Fail Cause');
        View::share('reject', 'Reject');
        View::share('modify', 'Modify');
        View::share('repeat', 'Repeat');
        View::share('stageConfig', OperationsUpgrade::STAGE_CONFIG);
        View::share('sigmaTableWidthDefaults', TableWidthPreferences::getDefaults());
        Job::observe(JobObserver::class);
        note::observe(NoteObserver::class);
        sCase::observe(OperationsDashboardObserver::class);
        Build::observe(OperationsDashboardObserver::class);
        caseTag::observe(OperationsDashboardObserver::class);
        abutmentDeliveryRecord::observe(OperationsDashboardObserver::class);
        tag::observe(OperationsDashboardObserver::class);
        device::observe(OperationsDashboardObserver::class);
        View::composer('*', function ($view) {
            static $cachedUserId = null;
            static $cachedWidthPrefs = [];
            static $cachedWidthPrefsResolved = false;
            static $cachedActiveEmployees = [];
            static $cachedActiveEmployeesResolved = false;

            if (auth()->check()) {
                $currentUserId = auth()->id();

                if (!$cachedWidthPrefsResolved || $cachedUserId !== $currentUserId) {
                    $cachedWidthPrefs = TableWidthPreferences::getForUser($currentUserId);
                    $cachedWidthPrefsResolved = true;
                    $cachedUserId = $currentUserId;
                    $cachedActiveEmployeesResolved = false;
                    $cachedActiveEmployees = [];
                }

                $view->with('sigmaTableWidthPrefs', $cachedWidthPrefs);
            } else {
                $view->with('sigmaTableWidthPrefs', []);
            }

            // Share active employees for admin impersonation
            // Exclude soft-deleted users and admins
            if (auth()->check() && auth()->user()->is_admin) {
                if (!$cachedActiveEmployeesResolved) {
                    $cachedActiveEmployees = \App\User::where('status', 1)
                        ->where('is_admin', 0)
                        ->whereNull('deleted_at')
                        ->select('id', 'first_name', 'last_name', 'name_initials')
                        ->orderBy('first_name')
                        ->orderBy('last_name')
                        ->get();
                    $cachedActiveEmployeesResolved = true;
                }

                $view->with('activeEmployees', $cachedActiveEmployees);
            }
        });
    }
}
