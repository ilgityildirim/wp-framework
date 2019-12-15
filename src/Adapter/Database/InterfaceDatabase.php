<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Database;

interface InterfaceDatabase
{
    /**
     * @return object
     */
    public function getClient();

    public function find(string $sql, array $conditions = []): ?array;

    public function findOne(string $sql, array $conditions = []): ?array;

    public function insert(string $tableName, array $data): bool;

    public function update(string $tableName, array $data, array $conditions = []): bool;

    public function delete(string $tableName, array $condition = []): bool;

    public function getVar(string $sql, int $x = 0, int $y = 0): ?string;

    /**
     * @param string $sql
     *
     * @return bool|int
     */
    public function exec(string $sql);

}
