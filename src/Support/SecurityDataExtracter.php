<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use IAmRGroot\LaravelData\OpenApi\Data\SecurityData;
use IAmRGroot\LaravelData\OpenApi\Enums\SecurityScheme;
use Illuminate\Routing\Route;

class SecurityDataExtracter
{
    /**
     * @return array<array-key,SecurityData>
     */
    public function extract(Route $route): array
    {
        /** @var string[] $middlewares */
        $middlewares = $route->middleware();


        /** @var SecurityData[] $securities */
        $securities = [];

        if (in_array('auth:sanctum', $middlewares)) {
            $securities[] = new SecurityData(SecurityScheme::BEARER);
        }

        return $securities;
    }
}