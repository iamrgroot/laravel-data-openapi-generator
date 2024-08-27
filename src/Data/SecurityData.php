<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Data;

use IAmRGroot\LaravelData\OpenApi\Enums\SecurityScheme;

class SecurityData
{
    public function __construct(
        public SecurityScheme $securityScheme,
    ) {
        
    }
}