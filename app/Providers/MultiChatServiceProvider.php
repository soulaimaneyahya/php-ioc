<?php

namespace App\Providers;

use PDO;
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
    public function registerServices(): void
    {
        /**
         * Register connection service
         */
        $this->register('connection', function() {
            return new PDO("mysql:host={$this->db['host']};dbname={$this->db['dbname']}", $this->db['username'], $this->db['password']);
        });
    }
}
