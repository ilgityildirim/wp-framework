<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter\Database;

use wpdb;

class WpDbAdapter extends AbstractDatabase
{
    /** @var wpdb  */
    private $client;

    public function __construct(wpdb $wpdb)
    {
        $this->client = $wpdb;
    }

    /**
     * @inheritDoc
     */
    public function getClient()
    {
        return $this->client;
    }

    public function find(string $sql, array $conditions = []): ?array
    {
        $records = $this->getResults($sql, $conditions);

        if (!$records) {
            return null;
        }

        return $records;
    }

    public function findOne(string $sql, array $conditions = []): ?array
    {
        $records = $this->getResults($sql, $conditions);

        if (!$records) {
            return null;
        }

        return (array) reset($records);
    }

    public function insert(string $tableName, array $data): bool
    {
        $result = $this->client->insert($tableName, $data);
        return $result && 1 >= $result;
    }

    public function update(string $tableName, array $data, array $conditions = []): bool
    {
        $result = $this->client->update($tableName, $data, $conditions);
        return $result && 1 >= $result;
    }

    public function delete(string $tableName, array $condition = []): bool
    {
        $result = $this->client->delete($tableName, $condition);
        return $result && 1 >= $result;
    }

    public function getVar(string $sql, int $x = 0, int $y = 0): ?string
    {
        return $this->client->get_var($sql, $x, $y);
    }

    public function prepare(string $sql, ...$args): string
    {
        return $this->client->prepare($sql, ...$args);
    }

    /**
     * @inheritDoc
     */
    public function exec(string $sql)
    {
        return $this->client->query($sql);
    }

    private function getResults($sql, array $conditions = [])
    {
        if (!$conditions) {
            $response = $this->client->get_results($sql);
        }
        else {
            $response = $this->client->get_results($this->client->prepare($sql, $conditions));
        }

        return $response ? array_values((array)$response) : null;
    }
    
    private function getLastErrors()
    {
        
    }
}
