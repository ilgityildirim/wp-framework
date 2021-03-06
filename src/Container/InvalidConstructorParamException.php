<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Container;

use Exception;

class InvalidConstructorParamException extends Exception
{
    public function __construct(string $parameter = '')
    {
        $message = sprintf('Invalid Constructor Parameter $%s', $parameter);
        parent::__construct($message);
    }
}
