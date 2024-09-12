<?php

namespace App\Container;

use Closure;
use Exception;
use App\Services\ServiceInterface;

class MultiChatContainer
{
    protected array $dependencies = [];

    protected static $instance = null;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
    }

    // Private __clone to prevent cloning
    private function __clone()
    {
    }

    /**
     * Get the singleton instance of the container.
     *
     * @return static
     */
    public static function getInstance(): static
    {
        if (self::$instance === null) {
            // new static() refers to the current class, not the parent container
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Method register
     *
     * @param string $name
     * @param Closure $closure
     * @return void
     */
    public function register(string $name, Closure $closure): void
    {
        $this->dependencies[$name] = $closure;
    }

    /**
     * Method resolve
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function resolve(string $name): mixed
    {
        if (isset($this->dependencies[$name])) {
            $closure = $this->dependencies[$name];

            return $closure();
        } else {
            throw new Exception("Dependency not found: " . $name);
        }
    }

    /**
     * Method scanDirectory
     *
     * @param string $directory
     * @return void
     */
    public function scanDirectory(string $directory): void
    {
        $files = glob($directory . '/*.php');

        foreach ($files as $file) {
            require_once($file);

            $className = basename($file, '.php');

            $this->register($className, function () use ($className): ServiceInterface {
                $className = 'App\Services\\' . $className;

                return new $className();
            });
        }
    }
}
