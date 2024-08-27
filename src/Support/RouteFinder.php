<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

class RouteFinder
{
    public function find(): RouteCollection
    {
        return Route::getRoutes();
    }
}