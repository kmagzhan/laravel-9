<?php

namespace App\Providers;

use App\Services\ElasticsearchHelper;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ElasticsearchHelperInterface::class, ElasticsearchHelper::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
