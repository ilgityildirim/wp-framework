<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter;

use TripleBits\WpFramework\Adapter\Exception\CreateDirectoryFailedException;

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
        return WP_PLUGIN_DIR . '/' . $this->pluginSlug . '/';
    }

    public function getVarDirectory(): string
    {
        return $this->getPluginDirectory() . 'var/';
    }

    /** @noinspection PhpUnused */
    public function getCacheDirectory(): string
    {
        return $this->getVarDirectory() . 'cache/';
    }

    /** @noinspection PhpUnused */
    public function getLogDirectory(): string
    {
        return $this->getPluginDirectory() . 'log/';
    }

    public function isDirectory(string $directory): bool
    {
        return is_dir($directory) && is_readable($directory);
    }

    public function createDirectory(string $directory): bool
    {
        if ($this->isDirectory($directory) || !wp_mkdir_p($directory)) {
            return true;
        }

        throw new CreateDirectoryFailedException($directory);
    }

    public function getUploadDirectory(): string
    {
        if (defined('UPLOADS')) {
            return trailingslashit(UPLOADS);
        }

        $directory = wp_upload_dir();
        if (isset($directory['basedir'])) {
            return trailingslashit($directory['basedir']);
        }

        // attempt to create our own
        $directory = trailingslashit(WP_CONTENT_DIR) . 'uploads/';
        if ($this->isDirectory($directory)) {
            return $directory;
        }

        $this->createDirectory($directory);
        return $directory;
    }

    public function getDirectory(string $directory): string
    {
        $this->createDirectory($directory);
        return $directory;
    }

    public function getCustomDataDirectory(string $relativePath): string
    {
        $uploadsDirectory = $this->getUploadDirectory();
        $fullPath = $uploadsDirectory . trim($relativePath, '/\\') . '/';
        return $this->getDirectory($fullPath);
    }
}
