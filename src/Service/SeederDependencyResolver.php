<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Service;

use Edgaras\WhatToDo\Exception\CircularDependencyException;

class SeederDependencyResolver
{
    /**
     * @param array<string> $classNames
     * @return array<string>
     * @throws CircularDependencyException
     */
    public function sortSeeders(array $classNames): array
    {
        $sorted = [];
        $resolved = [];

        while ($remaining = array_diff($classNames, array_keys($resolved))) {
            $progress = false;

            foreach ($remaining as $class) {
                $unresolvedDeps = array_diff($class::dependencies(), array_keys($resolved));

                if (empty($unresolvedDeps)) {
                    $sorted[] = $class;
                    $resolved[$class] = true;
                    $progress = true;
                }
            }

            if (!$progress) {
                throw new CircularDependencyException("Circular dependency detected");
            }
        }

        return $sorted;
    }
}