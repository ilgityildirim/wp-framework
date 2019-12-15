<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Command;

interface HandlerInterface
{
    public function addCommand(CommandInterface $command): void;

    public function handle(): void;
}
