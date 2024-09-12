<?php

namespace App\Providers;

use PDO;
use App\Connection\ConnectionService;
use App\Container\MultiChatContainer;

class MultiChatServiceProvider extends MultiChatContainer
{
    private array $db = [
        "host" => "localhost",
        "dbname" => "php-ioc",
        "username" => "root",
        "password" => ""
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function registerSharedServices(): void
    {
        /**
         * Register connection service as singleton
         */
        $this->registerShared('connection', function (): PDO {
            $connectionService = new ConnectionService($this->db);

            return $connectionService->connect();
        });
    }
}
