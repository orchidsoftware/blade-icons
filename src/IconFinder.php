<?php

declare(strict_types=1);

namespace Orchid\Icons;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class IconFinder
{
    /**
     * @var Collection
     */
    private Collection $directories;

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
            $prefix => realpath($directory),
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
        $prefix = Str::of($name)->before('.')->toString();
        $dir = $this->directories->get($prefix);

        if ($dir !== null) {
            return $this->getContent($name, $prefix, $dir);
        }

        // Failed to find the icon
        return $this->directories
            ->map(fn($dir) => $this->getContent($name, $prefix, $dir))
            ->filter()
            ->first();
    }

    /**
     * @param string $name
     * @param string $prefix
     * @param string $dir
     *
     * @return string
     */
    protected function getContent(string $name, string $prefix, string $dir)
    {
        $file = Str::of($name)
            ->when($prefix !== $name, fn($string) => $string->replaceFirst($prefix, ''))
            ->replaceFirst('.', '')
            ->replace('.', '/');

        $path = $dir . '/' . $file . '.svg';

        try {
            return file_get_contents($path);
        } catch (\Exception) {
            return null;
        }
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
