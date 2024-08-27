<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use IAmRGroot\LaravelData\OpenApi\Data\MethodData;
use IAmRGroot\LaravelData\OpenApi\Data\ParameterData;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionParameter;

class ParameterDataExtracter
{
    public function __construct(
        private TypeExtracter $typeExtracter,
    )
    {
        
    }
    /**
     * @return array
     */
    public function extract(MethodData $routeMethodData): array
    {
        /** @var string[] $uriParameters */
        $uriParameters = $routeMethodData->route->parameterNames();

        /** @var ReflectionParameter[] $methodParameters */
        $methodParameters = $routeMethodData->method->getParameters();

        $parameterData = [];

        foreach ($uriParameters as $uriParameter) {
            $isOptional = Str::contains($routeMethodData->route->uri, '?' . $uriParameter);

            $methodParameter = Arr::first(
                $methodParameters,
                fn (ReflectionParameter $methodParameter) => $methodParameter->getName() === $uriParameter,
            );

            $parameterData[] = new ParameterData(
                name: $methodParameter->getName(),
                in: 'path',
                description: $methodParameter->getName(),
                required: !$isOptional,
                type: $this->typeExtracter->extractParameter($methodParameter),
            );
        }

        return $parameterData;
    }



}