<?php

declare(strict_types=1);

namespace Orchid\Icons;

use Illuminate\View\Component;
use Illuminate\View\View;

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
     * @var \Orchid\Icons\IconFinder
     */
    protected IconFinder $finder;

    /**
     * Create a new component instance.
     *
     * @param string      $path
     * @param string|null $id
     * @param string|null $class
     * @param string|null $width
     * @param string|null $height
     * @param string      $role
     * @param string      $fill
     */
    public function __construct(
        IconFinder $finder,
        string $path,
        string $id = null,
        string $class = null,
        string $width = null,
        string $height = null,
        string $role = 'img',
        string $fill = 'currentColor',
    )
    {
        $this->path = $path;
        $this->id = $id;
        $this->class = $class;
        $this->width = $width ?? $finder->getDefaultWidth();
        $this->height = $height ?? $finder->getDefaultHeight();
        $this->role = $role;
        $this->fill = $fill;
        $this->finder = $finder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return callable
     */
    public function render(): callable
    {
        return function (array $data = []) {
            return view('blade-icon::icon', [
                'html' => $this->finder->loadFile($this->path),
                'data' => collect($this->extractPublicProperties())->merge($data['attributes'] ?? [])->filter(fn($value) => is_string($value)),
            ]);
        };
    }

    /**
     * @param ...$params
     *
     * @return \Illuminate\View\View
     */
    public static function make(...$params): View
    {
        return resolve(static::class, $params)->render()();
    }
}
