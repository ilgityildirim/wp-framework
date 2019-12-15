<?php

declare(strict_types=1);

namespace App\Service\Adapter;

class Directory
{
    /** @var string */
    private $pluginSlug;

    public function __construct(string $pluginSlug)
    {
        $this->pluginSlug = $pluginSlug;
    }

    public function getPluginDirectory(): string
    {
        return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->pluginSlug . DIRECTORY_SEPARATOR;
    }

    public function getVarDirectory(): string
    {
        return $this->getPluginDirectory() . 'var' . DIRECTORY_SEPARATOR;
    }

    /** @noinspection PhpUnused */
    public function getCacheDirectory(): string
    {
        return $this->getVarDirectory() . 'cache' . DIRECTORY_SEPARATOR;
    }

    /** @noinspection PhpUnused */
    public function getLogDirectory(): string
    {
        return $this->getPluginDirectory() . 'log' . DIRECTORY_SEPARATOR;
    }
}
