<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter;

use Symfony\Component\Filesystem\Exception\IOException;
use TripleBits\WpFramework\Filesystem\Filesystem;

class Directory
{
    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $slug;

    /** @var string */
    private $uploadDir;

    public function __construct(Filesystem $filesystem, string $slug)
    {
        $this->filesystem = $filesystem;
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getPluginDirectory(): string
    {
        return WP_PLUGIN_DIR . '/' . $this->slug . '/';
    }

    public function getCacheDirectory(): string
    {
        $dir = $this->getUploadDirectory() . 'cache/';
        $this->filesystem->mkdir($dir);
        return $dir;
    }

    public function getLogDirectory(): string
    {
        $dir = $this->getUploadDirectory() . 'log/';
        $this->filesystem->mkdir($dir);
        return $dir;
    }

    public function getUploadDirectory(): string
    {
        if ($this->uploadDir) {
            return $this->uploadDir;
        }

        $directory = wp_upload_dir();
        if (isset($directory['basedir'])) {
            $this->uploadDir = trailingslashit($directory['basedir']);
            return $this->uploadDir;
        }

        throw new IOException('Failed to create Upload directory');
    }

    public function getPluginUploadDirectory(): string
    {
        $uploadDir = trailingslashit($this->getUploadDirectory() . $this->getSlug());
        $this->filesystem->mkdir($uploadDir);
        return $uploadDir;
    }
}
