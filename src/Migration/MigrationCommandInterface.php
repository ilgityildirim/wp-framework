<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace TripleBits\WpFramework\Migration;

interface MigrationCommandInterface
{
    public function up(): void;

    public function down(): void;

    public function getTimestamp(): int;
}
