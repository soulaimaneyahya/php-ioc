<?php

namespace App\Connection;

use PDO;
use Exception;

class ConnectionService
{
    private array $dbConfig;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    /**
     * Establish a PDO connection and return it.
     *
     * @return PDO
     * @throws Exception
     */
    public function connect(): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->dbConfig['host']};dbname={$this->dbConfig['dbname']}",
                $this->dbConfig['username'],
                $this->dbConfig['password']
            );

            // Set PDO options for error handling and security
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (\PDOException $e) {
            // Handle connection failure and rethrow exception
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }
}
