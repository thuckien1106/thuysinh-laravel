<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

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

        // Locale + datetime helpers
        try { Carbon::setLocale(config('app.locale', 'vi')); } catch (\Throwable $e) {}
        Blade::directive('dt', function ($expression) {
            return "<?php echo optional($expression)->timezone(config('app.timezone'))->format('d/m/Y H:i'); ?>";
        });

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
