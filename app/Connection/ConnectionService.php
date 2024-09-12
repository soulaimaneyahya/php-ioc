<?php

namespace App\Connection;

use PDO;
use Exception;
use App\Services\ServiceInterface;

class ConnectionService implements ServiceInterface
{
    private string $id;
    private array $dbConfig;
    private ?PDO $pdo = null;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
        // Generate a unique ID for each instance
        $this->id = uniqid();
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
            $this->pdo = new PDO(
                "mysql:host={$this->dbConfig['host']};dbname={$this->dbConfig['dbname']}",
                $this->dbConfig['username'],
                $this->dbConfig['password']
            );

            // Set PDO options for error handling and security
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $this->pdo;
        } catch (\PDOException $e) {
            // Handle connection failure and rethrow exception
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function getConnectionId(): string
    {
        return $this->id;
    }
}
