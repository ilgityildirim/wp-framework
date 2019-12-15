<?php

declare(strict_types=1);

namespace App\Service\TemplateEngine;

use App\Service\Adapter\Directory;

class TemplateEngine implements TemplateEngineInterface
{

    /** @var string */
    private $slug;

    /** @var string */
    private $pathTemplate;

    /** @var string */
    private $pathPublic;

    public function __construct(Directory $directory, string $slug)
    {
        $this->slug = $slug;
        $this->pathTemplate = $directory->getPluginDirectory() . 'template' . DIRECTORY_SEPARATOR;
        $this->pathPublic = $directory->getPluginDirectory() . 'public' . DIRECTORY_SEPARATOR;
    }

    /** @noinspection PhpUnused */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /** @noinspection PhpUnused */
    public function getPathTemplate(): string
    {
        return $this->pathTemplate;
    }

    /** @noinspection PhpUnused */
    public function setPathTemplate(string $pathTemplate): void
    {
        $this->pathTemplate = $pathTemplate;
    }

    /** @noinspection PhpUnused */
    public function getPathPublic(): string
    {
        return $this->pathPublic;
    }

    /** @noinspection PhpUnused */
    public function setPathPublic(string $pathPublic): void
    {
        $this->pathPublic = $pathPublic;
    }

    public function render(string $path, array $params = []): string
    {
        $fullPath = $this->pathTemplate .  $path;
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
