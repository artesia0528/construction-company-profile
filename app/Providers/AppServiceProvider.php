<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        View::composer('components.layout.*', function ($view): void {
            $view->with('companySetting', CompanySetting::current());
        });

        View::composer('pages.*', function ($view): void {
            if (! isset($view->getData()['companySetting'])) {
                $view->with('companySetting', CompanySetting::current());
            }
        });
    }
}
