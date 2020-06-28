<?php

declare(strict_types=1);

namespace Orchid\Icons;

use DOMDocument;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class IconComponent extends Component
{
    /**
     * Prefix for cache
     */
    private const ICON_ORCHID_CACHE = 'ICON_ORCHID_CACHE_';

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
     * @var IconFinder
     */
    private $finder;

    /**
     * Icon tag
     *
     * @var string
     */
    private $path;

    /**
     * Create a new component instance.
     *
     * @param IconFinder  $finder
     * @param string      $path
     * @param string|null $id
     * @param string|null $class
     * @param string      $width
     * @param string      $height
     * @param string      $role
     * @param string      $fill
     */
    public function __construct(
        IconFinder $finder,
        string $path,
        string $id = null,
        string $class = null,
        string $width = '1em',
        string $height = '1em',
        string $role = 'img',
        string $fill = 'currentColor'
    )
    {
        $this->finder = $finder;
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
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $icon = Cache::rememberForever(self::ICON_ORCHID_CACHE.$this->path, function (){
            return $this->finder->loadFile($this->path);
        });

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
        $item = collect($dom->getElementsByTagName('svg'))->first();

        collect($this->data())
            ->except('attributes')
            ->filter(function ($value) {
                return $value !== null;
            })
            ->each(function ($value, $key) use ($item) {
                $item->setAttribute($key, $value);
            });

        return $dom->saveHTML();
    }
}
