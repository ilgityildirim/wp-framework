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

    protected function generateSql(array $order = [], ?int $limit = null, ?int $offset = null, $select = '*'): string
    {
        $sql = sprintf('SELECT %s FROM `%s`', $select, $this->prefixedTableName());

        if (!is_array($order)) {
            $sql .= ' ORDER BY ' . $this->getOrder($order);
        }

        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $sql .= ' OFFSET ' . $offset;
        }

        return $sql;
    }

    protected function getOrder(array $args = []): string
    {
        $order = '';
        $rotations = [ 'asc', 'desc'];

        foreach ($args as $key => $value) {
            $name = (new Strings)->decamelize($key);
            $value = strtolower($value);
            $rotation = in_array($value, $rotations, true) ? strtoupper($value) : 'DESC';
            $order .= $name . ' ' . $rotation . ', ';
        }

        return rtrim($order, ', ');
    }
}
