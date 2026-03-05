<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use IAmRGroot\LaravelData\OpenApi\Data\RouteData;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Ramsey\Collection\Set;

class RouteDataExtracter
{
    public function __construct(
        private MethodFinder $methodFinder,
        private SecurityDataExtracter $securityDataExtracter,
        private ParameterDataExtracter $parameterDataExtracter,
        private TypeExtracter $typeExtracter,
    ) {}

    /**
     * @return array<array-key,RouteData>
     */
    public function extractCollection(RouteCollection $routeCollection): array
    {
        $routesData = [];

        foreach ($routeCollection->getRoutes() as $route) {
            if ($data = $this->extract($route)) {
                $routesData = [
                    ...$routesData,
                    ...$data,
                ];
            }
        }

        return $routesData;
    }

    /**
     * @return RouteData[]
     */
    public function extract(Route $route): array
    {
        $controllerMethod = $this->methodFinder->find($route);

        $routeData = [];

        foreach ($route->methods as $routeMethod) {
            $routeData[] = new RouteData(
                route: $route,
                method: strtolower($routeMethod),
                securitySchemes: $this->securityDataExtracter->extract($route),
                parameters: $this->parameterDataExtracter->extract($controllerMethod),
                responseType: $this->typeExtracter->extractResponse($controllerMethod),
            );
        }

        return $routeData;
    }
}