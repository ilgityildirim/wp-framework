<?php

namespace TripleBits\WpFramework\Plugin;

use Exception;

class InvalidPluginException extends Exception
{

    public function __construct(string $pluginClass)
    {
        $message = sprintf('Plugin %s must implement PluginInterface', $pluginClass);
        parent::__construct($message);
    }
}
