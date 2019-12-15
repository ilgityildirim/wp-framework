<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\TemplateEngine;

use TripleBits\WpFramework\Adapter\DateTimeAdapter;
use TripleBits\WpFramework\Adapter\Directory;
use TripleBits\WpFramework\FlashBag\FlashBag;

class TemplateEngine implements TemplateEngineInterface
{

    /** @var FlashBag */
    private $flashBag;

    /** @var string */
    private $slug;

    /** @var string */
    private $path;

    /** @var string */
    private $url;

    public function __construct(Directory $directory, FlashBag $flashBag)
    {
        $this->flashBag = $flashBag;
        $this->slug = $directory->getSlug();
        $this->path = $directory->getPluginDirectory() . 'template' . DIRECTORY_SEPARATOR;
        $this->url = plugin_dir_url($directory->getPluginDirectory() . $this->slug . '.php');
    }

    public function getFlashBag(): FlashBag
    {
        return $this->flashBag;
    }

    /** @noinspection PhpUnused */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /** @noinspection PhpUnused */
    public function getPath(): string
    {
        return $this->path;
    }

    /** @noinspection PhpUnused */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /** @noinspection PhpUnused */
    public function getUrl(): string
    {
        return $this->url;
    }

    /** @noinspection PhpUnused */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function render(string $path, array $params = []): string
    {
        $fullPath = $this->path .  $path;
        if (!file_exists($fullPath)) {
            throw new TemplateEngineException('Template not found: ' . $fullPath);
        }

        extract($params, EXTR_SKIP);
        ob_start();

        /** @noinspection PhpIncludeInspection */
        require $fullPath;
        $result = ob_get_clean();

        return (string)$result;
    }

    /** @noinspection PhpUnused */
    protected function getDateTimeFormat(): string
    {
        return (new DateTimeAdapter)->getDateTimeFormat();
    }
}
