<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo;

use Edgaras\WhatToDo\Exception\CircularDependencyException;
use Edgaras\WhatToDo\Exception\MissingDependencyParameterException;
use Edgaras\WhatToDo\Exception\ServiceInstantiationException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var array<string, object> stores instantiated service objects
     */
    private array $services;

    /**
     * @var array<string, bool|float|int|string> stores configuration parameters
     */
    private array $parameters;

    /**
     * @var array<string, bool> tracks services currently being instantiated to prevent circular dependencies
     */
    private array $instantiating = [];

    /**
     * @param array<string, bool|float|int|string> $parameters configuration parameters like DB credentials or paths
     * @param array<string, object>                $services   pre-instantiated services, mapped by class name
     */
    public function __construct(array $parameters = [], array $services = [])
    {
        $this->parameters = $parameters;
        $this->services = $services;
    }

    public function has(string $serviceClass): bool
    {
        return isset($this->services[$serviceClass]);
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @throws MissingDependencyParameterException
     */
    public function getParameter(string $name): mixed
    {
        if (!$this->hasParameter($name)) {
            throw new MissingDependencyParameterException("Missing parameter: {$name}");
        }

        return $this->parameters[$name];
    }

    public function bind(string $serviceClass, object $service): void
    {
        $this->services[$serviceClass] = $service;
    }

    /**
     * @throws CircularDependencyException|ServiceInstantiationException
     */
    public function get(string $serviceClass): object
    {
        if (str_starts_with($serviceClass, 'Edgaras\WhatToDo\Model')) {
            throw new ServiceInstantiationException("Skipping Model classes: {$serviceClass}");
        }

        if ($serviceClass === Container::class) {
            return $this;
        }

        if ($this->has($serviceClass)) {
            return $this->services[$serviceClass];
        }

        if (isset($this->instantiating[$serviceClass])) {
            throw new CircularDependencyException("Circular dependency detected for: {$serviceClass}");
        }

        $this->instantiating[$serviceClass] = true;

        try {
            $reflectionClass = new \ReflectionClass($serviceClass);

            $dependencies = [];
            $constructor = $reflectionClass->getConstructor();
            $arguments = $constructor?->getParameters() ?? [];
            foreach ($arguments as $argument) {
                /** @phpstan-ignore method.notFound*/
                if ($argument->getType()->isBuiltin()) {
                    $dependencies[] = $this->resolveParameter($argument->getName());
                } else {
                    /** @phpstan-ignore method.notFound*/
                    $dependencies[] = $this->get($argument->getType()->getName());
                }
            }

            $instance = $reflectionClass->newInstanceArgs($dependencies);

            $this->services[$serviceClass] = $instance;
            unset($this->instantiating[$serviceClass]);

            return $instance;
        } catch (\Throwable $e) {
            throw new ServiceInstantiationException("Cannot instantiate {$serviceClass}: ".$e->getMessage());
        }
    }

    /**
     * Resolves a scalar parameter.
     *
     * @throws MissingDependencyParameterException
     */
    private function resolveParameter(string $parameterName): mixed
    {
        if (!isset($this->parameters[$parameterName])) {
            throw new MissingDependencyParameterException("Cannot resolve parameter: {$parameterName}");
        }

        return $this->parameters[$parameterName];
    }
}
