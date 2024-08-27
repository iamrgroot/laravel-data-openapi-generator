<?php

namespace IAmRGroot\LaravelData\OpenApi\Test\Mocks;

use Spatie\LaravelData\Data;

class ResponseMock extends Data
{
    public function __construct(
        public string $message = 'test',
    ) {
    }
}
