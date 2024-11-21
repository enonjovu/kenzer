<?php

declare(strict_types=1);

namespace Kenzer\Database;

use PDO;
use PDOStatement;

class Connection
{
    private PDO $connection;

    private PDOStatement $statement;

    public function __construct(string $username, string $password, array $config)
    {
        $dsn = 'mysql:'.http_build_query($config, '', ';');

        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    public function get()
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }
}
