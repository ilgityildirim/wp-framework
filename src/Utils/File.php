<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Utils;

class File
{

    public function humanReadableSize($bytes): string
    {
        $value = floor(log($bytes) / log(1024));
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        return sprintf('%.02F', $bytes / (1024 ** $value)) * 1 . ' ' . $sizes[$value];
    }
}
