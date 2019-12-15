<?php

declare(strict_types=1);

namespace App\Service\TemplateEngine;

interface TemplateEngineInterface
{
    public function render(string $path, array $params = []): string;
}
