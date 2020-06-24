<?php

declare(strict_types=1);

namespace Orchid\Icons\Tests;

use Orchestra\Testbench\TestCase;
use Orchid\Icons\IconServiceProvider;

/**
 * Class TestUnitCase.
 */
abstract class TestUnitCase extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [__DIR__.'/views']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
           IconServiceProvider::class
        ];
    }
}
