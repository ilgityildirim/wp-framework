<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\TemplateEngine;

interface TemplateEngineInterface
{
    public function render(string $path, array $params = []): string;
}
