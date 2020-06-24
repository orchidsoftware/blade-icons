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
    private $directories;

    /**
     * @var IconFinder
     */
    private $finder;

    /**
     * IconFinder constructor.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
        $this->directories = collect();
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
        $prefix = Str::beforeLast($name, '.');
        $name = Str::afterLast($name, '.') . '.svg';

        $icons = $this->finder
            ->ignoreUnreadableDirs()
            ->followLinks()
            ->in(
                $this->directories->get($prefix, $this->directories->toArray())
            )
            ->files()
            ->name($name);

        /** @var PathFilterIterator $iterator */
        $iterator = tap($icons->getIterator())
            ->rewind();

        /** @var SplFileInfo|null $file */
        $file = collect($iterator)
            ->filter(static function (SplFileInfo $file) use ($name) {
                return $file->getFilename() === $name;
            })->first();

        return optional($file)->getContents();
    }
}
