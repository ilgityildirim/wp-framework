<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\FlashBag;

use TripleBits\WpFramework\Adapter\Dto\HookDto;
use TripleBits\WpFramework\Adapter\Hooks;
use TripleBits\WpFramework\TemplateEngine\TemplateEngine;

class Registerer
{
    /** @var string */
    private $path;

    /** @var FlashBag */
    private $flashBag;

    /** @var Hooks */
    private $hooks;

    /** @var TemplateEngine */
    private $templateEngine;

    public function __construct(string $path, FlashBag $flashBag, Hooks $hooks, TemplateEngine $templateEngine)
    {
        $this->path = $path;
        $this->flashBag = $flashBag;
        $this->hooks = $hooks;
        $this->templateEngine = $templateEngine;
        $this->registerMessages();
    }

    public function registerMessages(): void
    {
        $dto = new HookDto;
        $dto->setHook('admin_notices');
        $dto->setComponent($this);
        $dto->setCallback('render');

        $this->hooks->addAction($dto);
    }

    public function render(): void
    {
        $messages = $this->flashBag->get();
        foreach (FlashBag::AVAILABLE_TYPES as $type) {
            echo $this->templateEngine->render($this->path, [
                'type' => $type,
                'messages' => $messages[$type],
            ]);
        }
    }
}
