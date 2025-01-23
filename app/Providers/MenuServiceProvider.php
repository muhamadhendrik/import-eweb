<?php

namespace App\Providers;

use App\Models\Acl\Menu;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer(
            ['layouts.*'],
            function ($view) {
                $menus = Menu::orderBy('parent_type', 'ASC')->get();
                // dd($menus);
                $view->with([
                    'menus' => $menus
                ]);
            }
        );
    }
}
