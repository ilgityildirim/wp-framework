<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Command;

interface CommandInterface
{
    public function execute(): void;
}
