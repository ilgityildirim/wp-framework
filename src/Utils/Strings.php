<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Utils;

class Strings
{

    public function decamelize($string): string
    {
        return strtolower(preg_replace(['#([a-z\d])([A-Z])#', '#([^_])([A-Z][a-z])#'], '$1_$2', $string));
    }

    public function camelize(string $string): string
    {
        return str_replace('_', null, lcfirst(ucwords($string, '_')));
    }
}
