<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Data;

class ParameterData
{
    public function __construct(
        public string $name,
        public string $in,
        public string $description,
        public bool $required,
        public DataType $type,
    ) {
        
    }
}