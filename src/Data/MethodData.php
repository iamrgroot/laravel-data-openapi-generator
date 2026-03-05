<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Data;

use Illuminate\Routing\Route;
use ReflectionFunction;
use ReflectionMethod;

class MethodData
{
    public function __construct(
        public Route $route,
        public ReflectionFunction | ReflectionMethod $method,
    ) {
        
    }
}