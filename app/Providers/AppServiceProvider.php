<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\MenuService;
use App\Services\PhotoService;
use App\Services\AudioService;
use Illuminate\Support\Facades\Auth;

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
        view()->composer('*', function ($view) {
            $request = app(Request::class);

            $counter = MenuService::getCounters();
            $sidebar = MenuService::getSidebar();
            $menu = MenuService::getNavbar();

            $data = array_merge($counter, compact('sidebar', 'menu'));

            $queryContent = $request->query('content');

            if ($queryContent) {
                $contentArray = explode('_', $queryContent);
                $user = User::find($contentArray[3]);

                $typeContent = $contentArray[2];

                $to = $request->query('to');
                $chat = $request->query('chat');

                $content = PhotoService::getPhotos($user, $typeContent, $to, $chat);
                $activeContent = $contentArray[1];

                $data = array_merge($data, compact('content', 'activeContent', 'typeContent'));
            }

            $view->with($data);
        });
    }
}
