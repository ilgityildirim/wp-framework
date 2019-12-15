<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Component;

use TripleBits\WpFramework\TemplateEngine\TemplateEngine;
use TripleBits\WpFramework\FlashBag\FlashBag;

abstract class AbstractRenderableComponent implements RenderableComponentInterface
{
    /** @var TemplateEngine  */
    protected $templateEngine;

    public function __construct(TemplateEngine $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    protected function renderTemplate($path, array $params = []): string
    {
        return $this->templateEngine->render($path, $params);
    }

    protected function getFlashBag(): FlashBag
    {
        return $this->templateEngine->getFlashBag();
    }
}
