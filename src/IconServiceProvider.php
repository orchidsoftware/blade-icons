<?php

declare(strict_types=1);

namespace Orchid\Icons;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class IconServiceProvider extends ServiceProvider
{
    /**
     * Register bindings the service provider.
     */
    public function register()
    {
        $this->app->singleton(IconFinder::class, static function () {
            return new IconFinder();
        });

        $this->loadViewsFrom(__DIR__.'/../views/', 'blade-icon');

        Blade::component('orchid-icon', IconComponent::class);
    }
}

