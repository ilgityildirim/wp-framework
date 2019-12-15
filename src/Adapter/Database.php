<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter;

use wpdb;
use TripleBits\WpFramework\Adapter\Database\InterfaceDatabase;
use TripleBits\WpFramework\Adapter\Database\WpDbAdapter;

class Database
{
    /** @var InterfaceDatabase  */
    private $client;

    /** @var WpDbAdapter */
    private $wpdba;

    /** @var wpdb */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->wpdba = new WpDbAdapter($this->wpdb);
        $this->client = $this->wpdba;
    }

    public function getClient(): ?InterfaceDatabase
    {
        return $this->client;
    }

    /** @noinspection PhpUnused */
    public function getWpdba(): ?WpDbAdapter
    {
        return $this->wpdba;
    }

    public function getPrefix(): string
    {
        return $this->wpdb->prefix;
    }

    /** @noinspection PhpUnused */
    public function provideSqlPrefix(?string $prefix): string
    {
        if (!$prefix) {
            $prefix = $this->getPrefix();
        }
        return str_replace('_', '\_', $prefix);
    }

    /** @noinspection PhpUnused */
    public function getCharset(): string
    {
        return $this->wpdb->get_charset_collate();
    }

    /**
     * @param string $statement
     *
     * @return int|bool
     */
    public function exec(string $statement)
    {
        return $this->client->exec($statement);
    }

    public function find(string $sql, array $conditions = []): ?array
    {
        return $this->client->find($sql, $conditions);
    }

    public function findOne(string $sql, array $conditions = []): ?array
    {
        return $this->client->findOne($sql, $conditions);
    }

    public function insert(string $tableName, array $data): bool
    {
        return $this->client->insert($tableName, $data);
    }

    public function update(string $tableName, array $data, array $conditions = []): bool
    {
        return $this->client->update($tableName, $data, $conditions);
    }

    public function delete(string $tableName, array $condition = []): bool
    {
        return $this->client->delete($tableName, $condition);
    }

    public function getVar(string $sql, int $x = 0, int $y = 0): ?string
    {
        return $this->client->getVar($sql, $x, $y);
    }

    public function prepare(string $sql, ...$args): string
    {
        return $this->client->prepare($sql, ...$args);
    }
}
