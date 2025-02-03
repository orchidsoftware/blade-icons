<?php

declare(strict_types=1);

namespace Orchid\Icons;

use Illuminate\View\Component;
use Illuminate\View\View;

class IconComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @param IconFinder $finder
     * @param string $path
     * @param string|null $id
     * @param string|null $class
     * @param string|null $width
     * @param string|null $height
     * @param string|null $role
     * @param string|null $fill
     */
    public function __construct(
        protected IconFinder $finder,
        public string $path,
        public ?string $id = null,
        public ?string $class = null,
        public ?string $width = null,
        public ?string $height = null,
        public ?string $role = 'img',
        public ?string $fill = 'currentColor',
    )
    {
        $this->width ??= $finder->getDefaultWidth();
        $this->height ??= $finder->getDefaultHeight();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return callable
     */
    public function render(): callable
    {
        return function (array $data = []) {
            $attributes = $data['attributes'] ?? [];

            return view('blade-icon::icon', [
                'html' => $this->finder->loadFile($this->path),
                'data' => collect($this->extractPublicProperties())
                    ->merge($attributes)
                    ->filter(fn($value) => is_string($value)),
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
