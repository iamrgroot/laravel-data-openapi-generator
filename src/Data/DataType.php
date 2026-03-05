<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Data;

use DateTimeInterface;

class DataType
{
    public function __construct(
        public string $type,
        public bool $nullable,
        public bool $isList = false,
        public bool $isRef = false,
    ) {}

    public function getFormat(): ?string
    {
        if (is_a($this->type, DateTimeInterface::class, true)) {
            return 'date-time';
        }

        return null;
    }
}