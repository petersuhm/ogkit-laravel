<?php

namespace Petersuhm\Ogkit\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Orchestra\Testbench\TestCase;
use Petersuhm\Ogkit\OgkitServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            OgkitServiceProvider::class,
        ];
    }

    #[Test]
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(OgkitServiceProvider::class, new OgkitServiceProvider(app()));
    }

    #[Test]
    public function it_adds_info_to_about_command()
    {
        $this->artisan('about', ['--json' => true])
            ->expectsOutputToContain('"ogkit":');
    }
}
