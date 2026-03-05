<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Support;

use IAmRGroot\LaravelData\OpenApi\Data\DataType;
use IAmRGroot\LaravelData\OpenApi\Data\MethodData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\ContextFactory;
use Illuminate\Support\Str;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use Spatie\LaravelData\DataCollection;

class TypeExtracter
{
    public function extractParameter(ReflectionParameter $parameter): DataType
    {
        $type = $parameter->getType();

        if (! $type instanceof ReflectionNamedType) {
            throw new RuntimeException(sprintf("Type of parameter %s could not be found", $parameter->getName()));
        }

        $typeName = $type->getName();

        if (is_a($typeName, Model::class, true)) {
            /** @var Model $instance */
            $instance  = (new $typeName());
            $typeName = $instance->getKeyType();
        }

        return new DataType(
            type: $typeName,
            nullable: $type->allowsNull(),
        );
    }

    public function extractResponse(MethodData $methodData): DataType
    {
        $returnType = $methodData->method->getReturnType();

        if (! $returnType instanceof ReflectionNamedType) {
            throw new RuntimeException(sprintf(
                "Return type of method %s could not be found for uri '%s'",
                $methodData->method->getName(),
                $methodData->route->uri,
            ));
        }

        $returnTypeName = $returnType->getName();

        $is_list = $returnTypeName === 'array'
            || is_a($returnTypeName, DataCollection::class, true)
            || is_a($returnTypeName, Collection::class, true);

        if ($is_list) {
            return $this->extractListTypeFromDocblock($methodData);
        }

        return new DataType(
            type: $returnTypeName,
            nullable: $returnType->allowsNull(),
            isRef: !$returnType->isBuiltin(),
        );
    }

    private function extractListTypeFromDocblock(MethodData $methodData): DataType
    {
        $docblock = $methodData->method->getDocComment();

        if (!$docblock) {
            throw new RuntimeException(sprintf(
                "Docblock of method %s with a list as return type could not be found (for uri %s)",
                $methodData->method->getName(),
                $methodData->route->uri,
            ));
        }

        $contextFactory = new ContextFactory();
        $context = $contextFactory->createFromReflector($methodData->method);

        $docblock = DocBlockFactory::createInstance()->create($docblock, $context);

        /** @var ?Return_ $returnTag */
        $returnTag = $docblock->getTagsByName('return')[0] ?? null;

        if (!$returnTag) {
            throw new RuntimeException(sprintf(
                "Return tag in docblock of method %s with a list as return type could not be found (for uri %s)",
                $methodData->method->getName(),
                $methodData->route->uri,
            ));
        }

        $returnTagType = $returnTag->getType();

        if (!$returnTagType instanceof AbstractList) {
            throw new RuntimeException(sprintf(
                "Return tag in docblock of method %s is not a list (for uri %s)",
                $methodData->method->getName(),
                $methodData->route->uri,
            ));
        }

        $returnTagValueType = Str::trim((string) $returnTagType->getValueType(), '\\');
        
        if (! class_exists($returnTagValueType)) {
            throw new RuntimeException(sprintf(
                "Could not find class %s in tag in docblock of method %s (for uri %s)",
                $returnTagValueType,
                $methodData->method->getName(),
                $methodData->route->uri,
            ));
        }

        return new DataType(
            type: $returnTagValueType,
            nullable: false,
            isList: true,
            isRef: true,
        );
    }
}