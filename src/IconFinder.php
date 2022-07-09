<?php

declare(strict_types=1);

namespace Orchid\Icons;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\PathFilterIterator;
use Symfony\Component\Finder\SplFileInfo;

class IconFinder
{
    /**
     * @var Collection
     */
    private Collection $directories;

    /**
     * Previously processed icons
     *
     * @var Collection
     */
    private Collection $cache;

    /**
     * @var string
     */
    private string $width = '1em';

    /**
     * @var string
     */
    private string $height = '1em';

    /**
     * IconFinder constructor.
     */
    public function __construct()
    {
        $this->directories = collect();
        $this->cache = collect();
    }

    /**
     * @return Collection
     */
    public function getDirectories(): Collection
    {
        return $this->directories;
    }

    /**
     * @param string $directory
     * @param string $prefix
     *
     * @return self
     */
    public function registerIconDirectory(string $prefix, string $directory): self
    {
        $this->directories = $this->directories->merge([
            $prefix => $directory,
        ]);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function loadFile(string $name): ?string
    {
        if ($this->cache->has($name)) {
            return $this->cache->get($name);
        }

        $prefix = Str::beforeLast($name, '.');
        $nameIcon = Str::afterLast($name, '.') . '.svg';

        $dirs = $this->directories->get($prefix, $this->directories->toArray());

        $icons = $this->getFinder()->in($dirs);

        /** @var PathFilterIterator $iterator */
        $iterator = tap($icons->getIterator())
            ->rewind();

        /** @var SplFileInfo|null $file */
        $file = collect($iterator)
            ->filter(static function (SplFileInfo $file) use ($nameIcon) {
                return $file->getFilename() === $nameIcon;
            })->first();

        $icon = optional($file)->getContents();

        $this->cache->put($name, $icon);

        return $icon;
    }

    /**
     * @return Finder
     */
    protected function getFinder(): Finder
    {
        return (new Finder())
            ->ignoreUnreadableDirs()
            ->followLinks()
            ->ignoreDotFiles(true);
    }

    /**
     * @param string $width
     * @param string $height
     *
     * @return $this
     */
    public function setSize(string $width = '1em', string $height = '1em'): IconFinder
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Return the default width.
     *
     * @return string
     */
    public function getDefaultWidth(): string
    {
        return $this->width;
    }

    /**
     * Return the default height.
     *
     * @return string
     */
    public function getDefaultHeight(): string
    {
        return $this->height;
    }
}
