<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Exception;

use RuntimeException;
use Throwable;

class CreateDirectoryFailedException extends RuntimeException
{
    public function __construct($directory, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Failed to create directory at %s', $directory);
        parent::__construct($message, $code, $previous);
    }
}
