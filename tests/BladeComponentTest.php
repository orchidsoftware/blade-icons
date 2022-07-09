<?php

declare(strict_types=1);

namespace Orchid\Icons\Tests;

use Illuminate\Support\Facades\Blade;
use Orchid\Icons\IconFinder;

class BladeComponentTest extends TestUnitCase
{
    public function testWithPrefixComponent(): void
    {
        $this->app->make(IconFinder::class)
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $view = view('icon-with-prefix')->render();

        $this->assertNotEmpty($view);
    }

    public function testOnlyNameComponent(): void
    {
        $this->app->make(IconFinder::class)
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $view = view('icon-only-name')->render();

        $this->assertNotEmpty($view);
    }


    public function testAttibutesComponent(): void
    {
        $this->app->make(IconFinder::class)
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $view = view('icon-only-name')->render();


        $this->assertStringContainsString('id="home"', $view);
        $this->assertStringContainsString('class="icon-big"', $view);
        $this->assertStringContainsString('width="2em"', $view);
        $this->assertStringContainsString('height="2em"', $view);
    }


    /**
     * https://github.com/laravel/framework/issues/32254
     */
    public function testLongContentComponent(): void
    {
        $this->app->make(IconFinder::class)
            ->registerIconDirectory('empty', __DIR__ . '/stubs');

        $view = view('icon-long-view-content')->render();

        $this->assertEmpty($view);
    }

    public function testDefaultIconSize():void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $view = Blade::render('<x-orchid-icon path="foo.house" />');

        $this->assertStringContainsString('height="54px"', $view);
        $this->assertStringContainsString('width="54px"', $view);
    }
}
