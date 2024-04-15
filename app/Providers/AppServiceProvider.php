<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Services\GeneralService;
use App\Services\MenuService;
use Carbon\Carbon;

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
        Carbon::setLocale('ru');

        view()->composer('*', function ($view) {
            $request = app(Request::class);

            $counter = MenuService::getCounters();
            $sidebar = MenuService::getSidebar();
            $menu = MenuService::getNavbar();

            $data = array_merge($counter, compact('sidebar', 'menu'));

            $dataPublication = GeneralService::openPublicationModal($request);

            $data = array_merge($data, $dataPublication);

            $view->with($data);
        });
    }
}
