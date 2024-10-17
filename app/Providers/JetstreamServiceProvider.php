<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\Master\Company;
use App\Models\Security\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
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
        $this->configurePermissions();

        Fortify::loginView(function () {
            $companies = Company::where('state', 1)->where('name', '<>', 'Todos')->orderby('name')->get();

            return view('auth.login', compact('companies'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            $company = Company::where('id', $request->company_id)->where('state', 1)->first();
            $user = User::where('username', $request->username)->first();

            if ($user &&
                $user->belongsToCompany($company) &&
                Hash::check($request->password, $user->password)) {

                $company_all = Company::where('name', 'Todos')->first();
                if ($user->belongsToCompany($company_all))
                    $roles = $user->roles()->get();
                else
                    $roles = $user->roles()->wherePivot('company_id', $user->current_company_id)->get();

                $ids = collect();
                foreach ($roles as $role) {
                    $ids = $ids->merge($role->menus->pluck('id'));
                    $ids = $ids->merge($role->menus->pluck('menu_id'));
                }

                $menus = Menu::whereIn('id', $ids)
                        ->groupBy('id', 'icon', 'name', 'order', 'route', 'active')
                        ->select('id', 'icon', 'name', 'order', 'route', 'active')
                        ->orderBy('id')
                        ->get();

                $user->current_company_id = $company->id;
                $user->menu = $menus->toJson();
                $user->save();

                return $user;
            }
        });

        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
