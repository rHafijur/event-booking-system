<?php
namespace Infrastructure;

use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class Container {
    protected static ?Container $instance = null;
    protected array $bindings = [];
    protected array $instances = [];

    // Get the global instance of the container
    public static function getInstance(): Container {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Bind an interface or class to a concrete implementation
    public function bind(string $abstract, callable|string $concrete) {
        $this->bindings[$abstract] = $concrete;
    }

    // Bind a singleton instance
    public function singleton(string $abstract, callable|string $concrete) {
        $this->instances[$abstract] = is_callable($concrete) ? $concrete($this) : new $concrete;
    }

    // Resolve dependencies automatically
    public function resolve(string $abstract) {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (!isset($this->bindings[$abstract])) {
            return $this->build($abstract);
        }

        $concrete = $this->bindings[$abstract];
        return is_callable($concrete) ? $concrete($this) : $this->build($concrete);
    }

    // Build a class using reflection
    protected function build(string $class) {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (!$constructor) {
            return new $class;
        }

        $dependencies = array_map(fn($param) => $this->resolveDependency($param), $constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }

    // Resolve method dependencies along with normal arguments
    public function call($object, string $method, array $params = []) {
        $reflector = new ReflectionMethod($object, $method);
        $dependencies = [];

        foreach ($reflector->getParameters() as $param) {
            $dependencies[] = $param->getType() && !$param->getType()->isBuiltin()
                ? $this->resolve($param->getType()->getName())
                : ($params[$param->getName()] ?? throw new Exception("Missing argument: " . $param->getName()));
        }

        return $reflector->invokeArgs($object, $dependencies);
    }

    // Resolve constructor dependencies
    protected function resolveDependency(ReflectionParameter $param) {
        return $param->getType() && !$param->getType()->isBuiltin()
            ? $this->resolve($param->getType()->getName())
            : throw new Exception("Cannot resolve dependency: " . $param->getName());
    }
}