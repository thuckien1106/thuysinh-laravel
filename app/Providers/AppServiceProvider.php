<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
        // Use Bootstrap 5 pagination views for links()
        if (method_exists(Paginator::class, 'useBootstrapFive')) {
            Paginator::useBootstrapFive();
        } else {
            Paginator::useBootstrap();
        }

        // Share categories and brands for header dropdowns
        View::composer('layouts.header', function ($view) {
            try {
                $categories = DB::table('categories')->orderBy('name')->limit(12)->get();
                $brands = DB::table('brands')->orderBy('name')->limit(12)->get();
            } catch (\Throwable $e) {
                $categories = collect();
                $brands = collect();
            }
            $view->with(compact('categories', 'brands'));
        });
    }
}
