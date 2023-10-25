<?php

namespace App\Providers;

use App\Services\ElasticsearchHelper;
use App\Services\RedisHelper;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
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
        $this->app->singleton(RedisHelperInterface::class, RedisHelper::class);
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
