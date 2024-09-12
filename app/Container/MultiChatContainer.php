<?php

namespace App\Container;

use Closure;
use Exception;
use ReflectionClass;
use App\Services\ServiceInterface;

class MultiChatContainer
{
    protected array $dependencies = [];
    protected array $sharedInstances = [];

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
     * Get the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Register a dependency in the container.
     *
     * @param string $name
     * @param Closure|string $definition
     * @param bool $shared
     * @return void
     */
    public function register(string $name, Closure|string $definition, bool $shared = false): void
    {
        $this->dependencies[$name] = compact('definition', 'shared');
    }

    /**
     * Register a shared (singleton) dependency.
     *
     * @param string $name
     * @param Closure|string $definition
     * @return void
     */
    public function registerShared(string $name, Closure|string $definition): void
    {
        $this->register($name, $definition, true);
    }

    /**
     * Resolve a dependency from the container.
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function resolve(string $name, array $parameters = []): mixed
    {
        if (isset($this->sharedInstances[$name])) {
            return $this->sharedInstances[$name];
        }

        if (isset($this->dependencies[$name])) {
            $definition = $this->dependencies[$name]['definition'];

            $object = $definition instanceof Closure
                ? $definition($this, $parameters)
                : $this->build($definition, $parameters);

            if ($this->dependencies[$name]['shared']) {
                $this->sharedInstances[$name] = $object;
            }

            return $object;
        }

        throw new Exception("Dependency not found: " . $name);
    }

    /**
     * Build a class instance, resolving its dependencies automatically.
     *
     * @param string $className
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function build(string $className, array $parameters = []): mixed
    {
        try {
            $reflector = new ReflectionClass($className);

            if (!$reflector->isInstantiable()) {
                throw new Exception("Class {$className} is not instantiable.");
            }

            $constructor = $reflector->getConstructor();

            if (is_null($constructor)) {
                return new $className;
            }

            $dependencies = $constructor->getParameters();
            $resolvedDependencies = [];

            foreach ($dependencies as $dependency) {
                $dependencyClass = $dependency->getType()?->getName();

                if ($dependencyClass && class_exists($dependencyClass)) {
                    $resolvedDependencies[] = $this->resolve($dependencyClass);
                } elseif (isset($parameters[$dependency->getName()])) {
                    $resolvedDependencies[] = $parameters[$dependency->getName()];
                } elseif ($dependency->isDefaultValueAvailable()) {
                    $resolvedDependencies[] = $dependency->getDefaultValue();
                } else {
                    throw new Exception(
                        "Unable to resolve dependency {$dependency->getName()} for class {$className}."
                    );
                }
            }

            return $reflector->newInstanceArgs($resolvedDependencies);
        } catch (Exception $e) {
            throw new Exception("Error building class {$className}: " . $e->getMessage());
        }
    }

    /**
     * Check if a dependency is registered.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->dependencies[$name]) || isset($this->sharedInstances[$name]);
    }

    /**
     * Remove a registered dependency.
     *
     * @param string $name
     * @return void
     */
    public function unregister(string $name): void
    {
        unset($this->dependencies[$name], $this->sharedInstances[$name]);
    }

    /**
     * Clear all registered dependencies and shared instances.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->dependencies = [];
        $this->sharedInstances = [];
    }

    /**
     * Scan a directory for services and automatically register them.
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

                return $this->build($className);
            }, false);
        }
    }
}
