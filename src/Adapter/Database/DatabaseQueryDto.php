<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Database;

class DatabaseQueryDto
{
    /** @var string */
    private $tableName;

    /** @var array */
    private $data = [];

    /** @var array  */
    private $dataValueMap = [];

    /** @var array  */
    private $conditions = [];

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data = []): void
    {
        $this->data = $data;
    }

    public function getDataValueMap(): array
    {
        return $this->dataValueMap;
    }

    public function setDataValueMap(array $dataValueMap = []): void
    {
        $this->dataValueMap = $dataValueMap;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function setConditions(array $conditions = []): void
    {
        $this->conditions = $conditions;
    }
}
