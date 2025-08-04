<?php

namespace Petersuhm\Ogkit;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Petersuhm\Ogkit\Urls\UrlProvider;
use Petersuhm\Ogkit\Urls\LocalUrlProvider;
use Petersuhm\Ogkit\Services\OgImageService;

class OgkitServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ogkit.php', 'ogkit');

        $this->app->bind(UrlProvider::class, LocalUrlProvider::class);
        $this->app->singleton('ogkit.service', OgImageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AboutCommand::add('ogkit', fn () => ['Version' => '0.0.1']);

        $this->registerRoutes();
    }

    public function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
