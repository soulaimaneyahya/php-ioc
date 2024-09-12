<?php

namespace App\Providers;

use App\Services\ServiceInterface;
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
        $this->registerShared('connection', function (): ServiceInterface {
            $connectionService = new ConnectionService($this->db);

            return $connectionService;
        });
    }
}
