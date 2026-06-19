<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
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
    View::composer('*', function ($view) {

        if (Auth::check()) {

            $user = Auth::user();

            $ownCompanies = DB::table('companies')
                ->where('UserId', $user->id)
                ->select('companies.id', 'companies.UserId as user_id', 'companies.CompanyName')
                ->get();

            $assignedCompanies = DB::table('user_company_map')
                ->join('companies', 'companies.id', '=', 'user_company_map.company_id')
                ->where('user_company_map.user_id', $user->id)
                ->select('companies.id', 'companies.UserId as user_id', 'companies.CompanyName')
                ->get();

            $companies = $ownCompanies
                ->merge($assignedCompanies)
                ->unique('id')
                ->values();

            if (!session()->has('active_company_user_id') && $companies->count() > 0) {
                session([
                    'active_company_user_id' => $companies[0]->user_id,
                    'active_company_id' => $companies[0]->id,
                ]);
            }

            $activeCompanyId = session('active_company_id');
            $activeCompanyUserId = session('active_company_user_id') ?? $user->id;


            $modules = DB::table('user_module_access')
                ->where('user_id', $user->id)
                ->where('company_id', $activeCompanyId)
                ->pluck('module_name')
                ->toArray();

            $activeCompanyUserId = session('active_company_user_id') ?? Auth::id();

            $activeUser = DB::table('users')
                ->where('id', $activeCompanyUserId)
                ->first();

            $view->with('activeUser', $activeUser);
            $view->with('companies', $companies);
            $view->with('modules', $modules);
        }
    });
}
}
