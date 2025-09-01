<?php

namespace DmitrijKalugin\WildberriesApiClient\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use DmitrijKalugin\WildberriesApiClient\WildberriesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            WildberriesServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Wildberries' => \DmitrijKalugin\WildberriesApiClient\Facades\Wildberries::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('wildberries.token', 'test_token');
        $app['config']->set('wildberries.sandbox', true);
    }
}
