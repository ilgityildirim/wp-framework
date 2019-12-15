<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Component;

interface ShortCodeInterface
{
    public function render($attributes, ?string $content = null): string;
}
