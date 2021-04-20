<?php

declare(strict_types=1);

namespace Orchid\Icons;

use Illuminate\Contracts\Support\Htmlable;

class Icon implements Htmlable
{
    /**
     * @var string|null
     */
    protected $content;

    /**
     * Icon constructor.
     *
     * @param string|null $content
     */
    public function __construct(string $content = null)
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function toHtml(): ?string
    {
        return $this->content;
    }
}
