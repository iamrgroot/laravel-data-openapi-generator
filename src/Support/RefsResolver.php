<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use IAmRGroot\LaravelData\OpenApi\Data\RefType;
use Illuminate\Support\Facades\App;
use Reflection;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Factories\DataPropertyFactory;

class RefsResolver
{
    public function resolveCollection(array $refs): array
    {
        
    }

    public function resolve(
        string $ref,
        bool $isList = false,
    ): RefType {
        if (! is_a($ref, Data::class, true)) {
            throw new RuntimeException(sprintf(
                "Ref %s is not a Data object",
                $ref,
            ));
        }

        $reflection = new ReflectionClass($ref);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $propertyTypes = [];

        foreach ($properties as $property) {
            $propertyTypes[$property->getName()] = $this->resolveProperty($reflection, $property);
        }

        return new RefType(
            type: $ref,
            properties: $propertyTypes,
        );
    }

    private function resolveProperty(ReflectionClass $reflection, ReflectionProperty $reflectionProperty): RefType
    {
        /** @var DataPropertyFactory $propertyFactory */
        $propertyFactory = App::make(DataPropertyFactory::class);
        $property = $propertyFactory->build($reflectionProperty, $reflection);

        $type = $property->type;

        if ($type->kind->isDataRelated()) {
            return $this->resolve($type->dataClass, isList: $type->kind->isDataCollectable());
        }

        // TODO resolve
        
    }
}