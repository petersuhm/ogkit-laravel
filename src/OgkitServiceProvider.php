<?php

namespace Petersuhm\Ogkit;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class OgkitServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ogkit.php', 'ogkit');

        $this->app->bind(\Petersuhm\Ogkit\Support\RenderUrlBuilder::class, function ($app) {
            return new \Petersuhm\Ogkit\Support\RenderUrlBuilder(config('ogkit.render_route'));
        });

        $this->app->singleton('ogkit.service', function ($app) {
            $cfg = $app['config']['ogkit'];

            $provider = match ($cfg['driver']) {
                'local' => new \Petersuhm\Ogkit\Urls\LocalUrlProvider('ogkit.image'),
                // 'hosted' => new \Petersuhm\Ogkit\Urls\HostedUrlProvider(
                //     $cfg['hosted']['endpoint'],
                //     $cfg['hosted']['tenant'],
                //     $cfg['hosted']['secret'],
                // ),
                default => throw new \InvalidArgumentException('Unknown ogkit driver'),
            };

            return new \Petersuhm\Ogkit\Services\OgImageService(
                urls: $provider,
                render: $app->make(\Petersuhm\Ogkit\Support\RenderUrlBuilder::class),
                hasher: new \Petersuhm\Ogkit\Support\VariantHasher(),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AboutCommand::add('ogkit', fn () => ['Version' => '0.0.1']);

        $this->registerRoutes();

        Blade::directive('ogimage', function ($expr) {
            // Usage: @ogimage('/posts/'.$post->slug, ['title' => $post->title])
            return "<?php echo e(\\Petersuhm\\Ogkit\\Facades\\OgImage::url(...$expr)); ?>";
        });
    }

    public function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
