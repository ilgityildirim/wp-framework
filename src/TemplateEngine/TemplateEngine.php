<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\TemplateEngine;

use TripleBits\WpFramework\Adapter\Directory;

class TemplateEngine implements TemplateEngineInterface
{

    /** @var string */
    private $slug;

    /** @var string */
    private $path;

    /** @var string */
    private $url;

    public function __construct(Directory $directory, string $slug)
    {
        $this->slug = $slug;
        $this->path = $directory->getPluginDirectory() . 'template' . DIRECTORY_SEPARATOR;
        $this->url = plugin_dir_url($directory->getPluginDirectory() . $slug . '.php');
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
        $dateFormat = get_option('date_format');
        $timeFormat = get_option('time_format');

        if (!$dateFormat) {
            $dateFormat = 'Y/m/d';
        }

        if (!$timeFormat) {
            $timeFormat = 'H:i:s';
        }

        return $dateFormat . ' ' . $timeFormat;
    }
}
