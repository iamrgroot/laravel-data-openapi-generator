<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Test\Mocks;

use Illuminate\Routing\Controller as LaravelController;

class Controller extends LaravelController
{
    public function method(
        int $i,
        string $string,
        ?int $nullableI,
        ?string $nullableString,
    ): ResponseMock {
        return new ResponseMock();
    }

    /**
     * @return ResponseMock[]
     */
    public function collectionMethod(): array {
        return [];
    }
}