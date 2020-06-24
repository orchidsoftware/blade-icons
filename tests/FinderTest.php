<?php

declare(strict_types=1);

namespace Orchid\Icons\Tests;

use Orchid\Icons\IconFinder;

class FinderTest extends TestUnitCase
{

    public function testRegisterOneRegister(): void
    {
        $finder = $this->getIconFinder()
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $icon = $finder->loadFile('foo.house');
        $this->assertNotNull($icon);

        $iconNotFound = $finder->loadFile('foo.people');

        $this->assertNull($iconNotFound);
    }

    public function testRegisterManyDirectory(): void
    {
        $finder = $this->getIconFinder()
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo')
            ->registerIconDirectory('bar', __DIR__ . '/stubs/bar');

        $icon = $finder->loadFile('bar.kidney');
        $this->assertNotNull($icon);

        $icon = $finder->loadFile('foo.house');
        $this->assertNotNull($icon);
    }

    public function testRegisterSinglenton(): void
    {
        $this->getIconFinder()
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $otherFinderIcon = $this->getIconFinder()->loadFile('foo.house');

        $this->assertNotNull($otherFinderIcon);
    }

    /**
     * @return IconFinder
     */
    private function getIconFinder(): IconFinder
    {
        return $this->app->make(IconFinder::class);
    }
}
