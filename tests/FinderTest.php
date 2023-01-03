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

    public function testRegisterSingleton(): void
    {
        $this->getIconFinder()
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');

        $otherFinderIcon = $this->getIconFinder()->loadFile('foo.house');

        $this->assertNotNull($otherFinderIcon);
    }

    public function testRegisterManyIcon(): void
    {
        $start = microtime(true);
        $iconArrayNotFound = [];
        $this->addIconToArray($iconArrayNotFound);
        $first = microtime(true);
        $this->addIconToArray($iconArrayNotFound);
        $second = microtime(true);
        $delta_first = $first - $start;
        $delta_second = $second - $first;


        $this->assertNotNull($iconArrayNotFound[0]);
        $this->assertLessThan($delta_first * 2, $delta_second, '$delta_first: ' . $delta_first . ', $delta_second: ' . $delta_second);
    }

    public function testRegisterNotIcon(): void
    {
        $start = microtime(true);
        $iconArrayNotFound = [];
        $this->addIconToArray($iconArrayNotFound, 'none');
        $first = microtime(true);
        $this->addIconToArray($iconArrayNotFound, 'none');
        $second = microtime(true);
        $delta_first = $first - $start;
        $delta_second = $second - $first;

        $this->assertNull($iconArrayNotFound[0]);
        $this->assertLessThan($delta_first * 2, $delta_second, '$delta_first: ' . $delta_first . ', $delta_second: ' . $delta_second);
    }


    /**
     * @return IconFinder
     */
    private function getIconFinder(): IconFinder
    {
        return $this->app->make(IconFinder::class);
    }

    /**
     * @param        $iconArray
     * @param string $icon
     * @param int    $count
     */
    private function addIconToArray(&$iconArray, $icon = 'house', $count = 100): void
    {
        $finder = $this->getIconFinder()
            ->registerIconDirectory('foo', __DIR__ . '/stubs/foo');
        for ($i = 0; $i < $count; $i++) {
            $iconArray[] = $finder->loadFile('foo.' . $icon);
        }
    }
}
