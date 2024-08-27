<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use Closure;
use IAmRGroot\LaravelData\OpenApi\Data\MethodData;
use Illuminate\Routing\Route;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use RuntimeException;

class MethodFinder
{
    public function find(Route $route): MethodData
    {
        $uses = $route->action['uses'];

        if (is_string($uses)) {
            return new MethodData(
                route: $route,
                method: (new ReflectionClass($route->getController()))
                    ->getMethod($route->getActionMethod()),
            );
        }
        
        if ($uses instanceof Closure) {
            return new MethodData(
                route: $route,
                method: new ReflectionFunction($uses),
            );
        }
            
        throw new RuntimeException(sprintf(
            'Could not find method from route \'%s\'',
            $route->getName() ?? $route->uri
        ));
    }
}