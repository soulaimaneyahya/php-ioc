<?php

namespace App\Providers;

use Closure;
use Exception;

class AppServiceProvider
{
    protected $dependencies = array();

    public function __construct()
    {
    }

    /**
     * register
     *
     * @param  string $name
     * @param  Closure $closure
     * @return void
     */
    public function register(string $name, Closure $closure)
    {
        $this->dependencies[$name] = $closure;
    }

    /**
     * resolve
     *
     * @param  string $name
     * @return void
     */
    public function resolve(string $name)
    {
        if (isset($this->dependencies[$name])) {
            $closure = $this->dependencies[$name];
            return $closure();
        } else {
            throw new Exception("Dependency not found: " . $name);
        }
    }

    /**
     * scanDirectory
     *
     * @param  string $directory
     * @return void
     */
    public function scanDirectory(string $directory): void
    {
        $files = glob($directory . '/*.php');

        foreach ($files as $file) {
            require_once($file);
            $className = basename($file, '.php');

            $this->register($className, function () use ($className) {
                $className = 'App\Services\\' . $className;

                return new $className();
            });
        }
        // dump($directory, $this->dependencies);
    }
}
