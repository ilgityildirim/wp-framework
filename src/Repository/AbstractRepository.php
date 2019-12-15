<?php

/** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);

namespace TripleBits\WpFramework\Repository;

use TripleBits\WpFramework\Adapter\Database;
use TripleBits\WpFramework\Utils\Strings;

abstract class AbstractRepository
{
    /** @var Database */
    protected $database;

    public function __construct(Database $database = null)
    {
        $this->database = $database ?? new Database;
    }

    abstract public function prefixedTableName(): string;

    public function countAll(): int
    {
        return (int) $this->database->getVar(sprintf('SELECT COUNT(1) FROM `%s`', $this->prefixedTableName()));
    }

    public function findOneBySqlDto(SqlDto $dto): ?array
    {
        return $this->findResultsBySqlDto($dto, true);
    }

    public function findBySqlDto(SqlDto $dto): ?array
    {
        return $this->findResultsBySqlDto($dto, false);
    }

    protected function findResultsBySqlDto(SqlDto $dto, bool $isSingle): ?array
    {
        $dto->setTableName($this->prefixedTableName());

        $methodName = $isSingle ? 'findOne' : 'find';
        $result = $this->database->{$methodName}($dto->generateSql(), $dto->getConditionValues());

        if (!$result) {
            return null;
        }

        return (array) $result;
    }
}
