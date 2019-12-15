<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Utils;

use TripleBits\WpFramework\Adapter\Directory;
use TripleBits\WpFramework\Filesystem\Filesystem;

class Cache
{
    protected const DEFAULT_LIFETIME = 2592000; // 30 days
    protected const EXTENSION = 'cache';

    /** @var Filesystem */
    private $filesystem;

    /** @var Directory */
    private $directory;

    /** @var int */
    private $lifetime;

    /** @var string */
    private $path;

    /** @var string */
    private $filename;

    /** @var string */
    private $filePath;

    public function __construct(Filesystem $filesystem, Directory $directory)
    {
        $this->filesystem = $filesystem;
        $this->directory = $directory;
        $this->setDefaults();
    }

    public function setDefaults(): void
    {
        $this->setPath($this->directory->getCacheDirectory());
        $this->setLifetime(self::DEFAULT_LIFETIME);
    }

    /**
     * @param null|mixed $default
     *
     * @return array|mixed|object|null
     */
    public function get($default = null)
    {
        if (!$this->filePath || !$this->isValid()) {
            return $default;
        }

        // unserialize() is not safe, vulnerable to remote code execution via PHP Object Injection
        // see https://owasp.org/www-community/vulnerabilities/PHP_Object_Injection
        return json_decode(file_get_contents($this->filePath));
    }

    /**
     * @param mixed $value
     */
    public function save($value): void
    {
        $this->filesystem->dumpFile($this->filePath, json_encode($value));
    }

    public function getLifetime(): int
    {
        return $this->lifetime ?? self::DEFAULT_LIFETIME;
    }

    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
        $this->initializeFilePath();
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename)
    {
        $this->filename = $filename;
        $this->initializeFilePath();
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    protected function isValid(): bool
    {
        if (!$this->filesystem->exists($this->filePath)) {
            return false;
        }

        if (!$this->isExpired()) {
            return true;
        }

        $this->remove();
        return false;
    }

    protected function remove(): void
    {
        $this->filesystem->remove($this->filePath);
    }

    protected function isExpired(): bool
    {
        return time() - filemtime($this->filePath) >= $this->lifetime;
    }

    private function initializeFilePath(): void
    {
        $this->filePath = $this->path;
        if ($this->filename) {
            $this->filePath .= $this->filename . '.' . self::EXTENSION;
        }
    }
}
