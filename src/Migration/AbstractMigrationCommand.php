<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace TripleBits\WpFramework\Migration;

use TripleBits\WpFramework\Adapter\Database;
use ReflectionClass;

abstract class AbstractMigrationCommand implements MigrationCommandInterface
{
    /** @var Database */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getPrefix(): string
    {
        return $this->database->getPrefix();
    }

    public function getTimestamp(): int
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return (int) str_replace('Migration', null, (new ReflectionClass($this))->getShortName());
    }
}
