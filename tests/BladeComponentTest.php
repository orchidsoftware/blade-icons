<?php

declare(strict_types=1);

namespace Orchid\Icons\Tests;

use Illuminate\Support\Facades\Blade;
use Orchid\Icons\IconComponent;
use Orchid\Icons\IconFinder;

class BladeComponentTest extends TestUnitCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('view:clear');
    }

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

    public function testDefaultIconSize(): void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $view = Blade::render('<x-orchid-icon path="foo.house" />');

        $this->assertStringContainsString('height="54px"', $view);
        $this->assertStringContainsString('width="54px"', $view);
    }

    public function testMixedIconWithoutPath(): void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $view = Blade::render('<x-orchid-icon path="foo.regular.address-book" />');
        $this->assertNotEmpty($view);

        $view = Blade::render('<x-orchid-icon path="regular.address-book" />');
        $this->assertEmpty($view);
    }

    public function testHtmlAttributes(): void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('feather', __DIR__ . '/stubs/feather');

        $view = Blade::render('<x-orchid-icon path="feather.alert-triangle" />');
        $this->assertNotEmpty($view);

        $this->assertStringContainsString('stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"', $view);

        $view = Blade::render('<x-orchid-icon path="feather.alert-triangle" class="test" stroke-width="3" />');

        $this->assertStringNotContainsString('stroke-width="2"', $view);
        $this->assertStringContainsString('stroke-width="3"', $view);
    }

    public function testHtmlAttributesDuplicate(): void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('feather', __DIR__ . '/stubs/feather');

        $view = Blade::render('<x-orchid-icon path="feather.alert-triangle" id="2" :id="3" />');
        $this->assertStringContainsString('id="3"', $view);

        $view = Blade::render('<x-orchid-icon path="feather.alert-triangle" :id="3" id="2" />');
        $this->assertStringContainsString('id="2"', $view);
    }

    public function testAverageSpeed(): void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('feather', __DIR__ . '/stubs/feather');


        collect(range(0, 10000))->each(function (int $key) {
            $view = Blade::render('<x-orchid-icon path="feather.alert-triangle" id="2" :id="$id" />', ['id' => $key]);
            $this->assertStringContainsString('id="' . $key, $view);
        });
    }

    public function testStaticRender():void
    {
        $this->app->make(IconFinder::class)
            ->setSize('54px', '54px')
            ->registerIconDirectory('feather', __DIR__ . '/stubs/feather');

        $view = IconComponent::make(
            path: 'feather.alert-triangle',
            id: 2,
            class: 'test',
        );

        $this->assertStringContainsString('id="2"', $view->toHtml());
        $this->assertStringContainsString('class="test"', $view->toHtml());
        $this->assertStringContainsString('stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"', (string) $view);
    }

}
