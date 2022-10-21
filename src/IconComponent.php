<?php

declare(strict_types=1);

namespace Orchid\Icons;

use DOMDocument;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\View\Component;

class IconComponent extends Component
{

    /**
     * @var string|null
     */
    public $class;

    /**
     * @var string
     */
    public $width;

    /**
     * @var string
     */
    public $height;

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $fill;

    /**
     * @var string
     */
    public $id;

    /**
     * Icon tag
     *
     * @var string
     */
    public $path;

    /**
     * Create a new component instance.
     *
     * @param string      $path
     * @param string|null $id
     * @param string|null $class
     * @param string      $width
     * @param string      $height
     * @param string      $role
     * @param string      $fill
     */
    public function __construct(
        string $path,
        string $id = null,
        string $class = null,
        string $width = null,
        string $height = null,
        string $role = 'img',
        string $fill = 'currentColor'
    )
    {
        $this->path = $path;
        $this->id = $id;
        $this->class = $class;
        $this->width = $width;
        $this->height = $height;
        $this->role = $role;
        $this->fill = $fill;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return string
     */
    public function render()
    {
        $factory = $this->factory();

        $factory->addNamespace(
            '__components__orchid__icons',
            $directory = Container::getInstance()['config']->get('view.compiled')
        );

        $hash = sha1(collect($this->extractPublicProperties())->implode('-'));

        if (! is_file($viewFile = $directory.'/'.$hash.'.blade.php')) {
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $contents = $this->renderIcon();

            file_put_contents($viewFile, $contents);
        }

        return view('__components__orchid__icons::'.basename($viewFile, '.blade.php'));
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function renderIcon(): string
    {
        $finder = app()->make(IconFinder::class);

        $this->width = $this->width ?? $finder->getDefaultWidth();
        $this->height = $this->width ?? $finder->getDefaultHeight();

        $icon = $finder->loadFile($this->path);

        return $this->setAttributes($icon);
    }

    /**
     * @param string|null $icon
     *
     * @return string
     */
    private function setAttributes(?string $icon): string
    {
        if ($icon === null) {
            return '';
        }

        $dom = new DOMDocument();
        $dom->loadXML($icon);

        /** @var \DOMElement $item */
        $item = Arr::first($dom->getElementsByTagName('svg'));

        collect($this->extractPublicProperties())
            ->except(['attributes'])
            ->filter(static fn($value) => is_string($value))
            ->each(static fn(string $value, string $key) => $item->setAttribute($key, $value));

        return $dom->saveHTML();
    }
}
