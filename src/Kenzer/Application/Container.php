<?php

namespace Kenzer\Application;
use Closure;
use Kenzer\Exception\Application\BindingNotFoundException;
use Kenzer\Exception\Application\ContainerException;
use ReflectionFunction;
use ReflectionMethod;

class Container
{
    private array $entries = [];
    private array $singletonEntries = [];

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            return array_key_exists($id, $this->singletonEntries) ?
                $this->resolveSingletonEntry($id)
                : $this->resolveEntry($id);
        }

        return $this->resolve($id);
    }


    protected function resolveEntry(string $id)
    {
        $entry = $this->entries[$id];

        if (is_string($entry) && class_exists($entry)) {
            return $this->resolve($entry);
        }

        if (is_callable($entry)) {
            return $this->callFunction($entry);
        }

        return $entry;
    }


    protected function resolveSingletonEntry(string $id)
    {
        return $this->singletonEntries[$id] ??= $this->resolveEntry($id);
    }


    public function has(string $id) : bool
    {
        return isset($this->entries[$id]);
    }

    public function bind(string $id, mixed $concrete) : void
    {
        $this->entries[$id] = $concrete;
    }

    public function singleton(string $id, mixed $concrete) : void
    {
        $this->entries[$id] = $concrete;
        $this->singletonEntries[$id] = null;
    }


    public function call(mixed $callable, array $options = [])
    {
        if (is_string($callable)) {
            if (! class_exists($callable)) {
                throw new ContainerException("$callable does not exist");
            }

            $class = $this->get($callable);

            if (! method_exists($class, '__invoke')) {
                throw new ContainerException("$callable is not invokable");
            }

            return $this->callClassMethod($class, '__invoke', $options);
        }

        if (is_callable($callable) || $callable instanceof Closure) {
            return $this->callFunction($callable, $options);
        }

        if (is_array($callable)) {
            if (count($callable) != 2) {
                throw new ContainerException("an array must have two items the class and the method");
            }

            [$classString, $method] = $callable;

            if (! class_exists($classString)) {
                throw new ContainerException("$classString does not exist");
            }

            $class = $this->get($classString);

            if (! method_exists($class, $method)) {
                throw new ContainerException("'$classString' does not have method '$method'");
            }

            return $this->callClassMethod($class, $method, $options);
        }


        throw new ContainerException("failed to resolve $callable");
    }

    protected function callClassMethod(object $class, string $method, array $options = [])
    {
        $reflectionMethod = new ReflectionMethod($class, $method);

        $dendencies = $this->getCallableDependencies($reflectionMethod, $options);

        return $reflectionMethod->invokeArgs($class, $dendencies);
    }

    protected function callFunction(callable|string $func, array $options = [])
    {
        $reflection = new ReflectionFunction($func);

        $dependencies = $this->getCallableDependencies($reflection, $options);

        return $reflection->invokeArgs($dependencies);
    }

    protected function getCallableDependencies(\ReflectionFunctionAbstract $func, array $options = [])
    {
        return $this->getDependencies($func->getName(), $func->getParameters(), $options);
    }

    public function resolve(string $id, array $options = [])
    {
        // 1. Inspect the class that we are trying to get from the container
        try {
            $reflectionClass = new \ReflectionClass($id);
        } catch (\ReflectionException $e) {
            throw new BindingNotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException('Class "' . $id . '" is not instantiable');
        }

        // 2. Inspect the constructor of the class
        $constructor = $reflectionClass->getConstructor();

        if (! $constructor) {
            return new $id;
        }

        // 3. Inspect the constructor parameters (dependencies)
        $parameters = $constructor->getParameters();

        if (! $parameters) {
            return new $id;
        }

        // 4. If the constructor parameter is a class then try to resolve that class using the container
        $dependencies = $this->getDependencies($id, $parameters, $options);

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    protected function getDependencies(string $id, array $reflectionParameters, array $options = [])
    {
        return array_map(
            function (\ReflectionParameter $param) use ($id, &$options) {
                $name = $param->getName();
                $type = $param->getType();

                if (array_key_exists($name, $options)) {
                    return $options[$name];
                }

                if (! $type) {
                    throw new ContainerException(
                        'Failed to resolve class "' . $id . '" because param "' . $name . '" is missing a type hint'
                    );
                }

                if ($type instanceof \ReflectionUnionType) {
                    throw new ContainerException(
                        'Failed to resolve class "' . $id . '" because of union type for param "' . $name . '"'
                    );
                }

                if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                    return $this->get($type->getName());
                }

                throw new ContainerException(
                    'Failed to resolve class "' . $id . '" because invalid param "' . $name . '"'
                );
            },
            $reflectionParameters
        );
    }
}
