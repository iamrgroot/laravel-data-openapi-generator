<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Data;

use Illuminate\Routing\Route;

class RouteData
{
    public function __construct(
        public Route $route,
        public string $method,
        public array $securitySchemes,
        public array $parameters,
        public DataType $responseType,
    ) {
        
    }
}