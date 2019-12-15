<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Filesystem;

use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;

class Filesystem extends BaseFilesystem
{
    public const DEFAULT_CHMOD = 0775;

    /**
     * @inheritDoc
     */
    public function mkdir($dirs, int $mode = self::DEFAULT_CHMOD): void
    {
        parent::mkdir($dirs, $mode);
    }

    public function humanReadableSize($bytes): string
    {
        $value = floor(log($bytes) / log(1024));
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        return sprintf('%.02F', $bytes / (1024 ** $value)) * 1 . ' ' . $sizes[$value];
    }
}
