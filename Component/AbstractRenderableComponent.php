<?php

declare(strict_types=1);

namespace App\Service\Component;

use App\Service\Adapter\Hooks;
use App\Service\TemplateEngine\TemplateEngine;

abstract class AbstractRenderableComponent extends AbstractComponent implements RenderableComponentInterface
{
    /** @var TemplateEngine  */
    protected $templateEngine;

    public function __construct(Hooks $hooks, TemplateEngine $templateEngine)
    {
        parent::__construct($hooks);
        $this->templateEngine = $templateEngine;
    }

    protected function renderTemplate($path, array $params = [])
    {
        return $this->templateEngine->render($path, $params);
    }
}
