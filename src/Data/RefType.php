<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Data;

class RefType
{
    public function __construct(
        public string $type,
        /** @var array<string,DataType> */
        public array $properties = [],
    ) {}
}