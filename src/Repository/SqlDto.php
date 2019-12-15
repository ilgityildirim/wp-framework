<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Repository;

use TripleBits\WpFramework\Utils\Strings;
use TripleBits\WpFramework\Traits\HydrateTrait;
use RuntimeException;

class SqlDto
{

    use HydrateTrait;

    public const ORDER_ASC = 'asc';
    public const ORDER_DESC = 'desc';

    /** @var string */
    private $select = '*';

    /** @var string */
    private $tableName;

    /** @var array */
    private $conditions = [];

    /** @var array */
    private $order = [];

    /** @var int|null */
    private $limit;

    /** @var int|null */
    private $offset;

    public function generateSql(): string
    {
        $sql = sprintf('SELECT %s FROM `%s`', $this->select, $this->tableName);
        $sql .= $this->generateSqlWhere();
        $sql .= $this->generateSqlOrder();

        if (null !== $this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if (null !== $this->offset) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return $sql;
    }

    public function getConditionValues(): array
    {
        $values = [];
        foreach ($this->conditions as $key => $value) {
            if (!is_int($key)) {
                $values[] = $value;
            }
        }
        return $values;
    }

    public function getSelect(): string
    {
        return $this->select;
    }

    public function setSelect(string $select): void
    {
        $this->select = $select;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function getConditions(): array
    {
        return $this->conditions ?? [];
    }

    public function setConditions(?array $conditions): void
    {
        $this->conditions = $conditions;
    }

    public function getOrder(): array
    {
        return $this->order ?? [];
    }

    public function setOrder(?array $order): void
    {
        $this->order = $order;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }

    protected function generateSqlWhere(): string
    {
        if (!$this->conditions) {
            return '';
        }

        $where = ' WHERE ';
        foreach ($this->conditions as $key => $value) {
            if (is_int($key) && is_string($value)) {
                $where .= $value . ' AND ';
                continue;
            }

            $name = Strings::decamelize($key);
            $where .= $name . ' = ' . $this->guessSpecifier($value) . ' AND ';
        }

        return rtrim($where, ' AND ');
    }

    protected function generateSqlOrder(): string
    {
        if (!$this->order) {
            return '';
        }

        $rotations = [
            self::ORDER_ASC,
            self::ORDER_DESC,
        ];

        $order = ' ORDER BY ';
        foreach ($this->order as $key => $value) {
            $name = Strings::decamelize($key);
            $value = strtolower($value);
            $rotation = in_array($value, $rotations, true) ? strtoupper($value) : 'DESC';
            $order .= $name . ' ' . $rotation . ', ';
        }

        return rtrim($order, ', ');
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function guessSpecifier($value): string
    {
        $type = gettype($value);
        switch ($type) {
            case 'string':
                return '%s';
            case 'integer':
                return '%d';
            case 'double':
                return '%f';
        }

        throw new RuntimeException(sprintf(
            'SqlDto::guessSpecifier; %s is not a supported type',
            $type
        ));
    }
}
