<?php

declare(strict_types=1);

use IAmRGroot\LaravelData\OpenApi\Test\Mocks\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

beforeAll(function () {

    Route::prefix('openapi')->group(function () {
        Route::get('{i}/{string}/{nullableI}/{?nullableString}', [Controller::class, 'method']);
        Route::get('collection', [Controller::class, 'collectionMethod']);
    });
});

it('can generate openapi json', function() {
    Artisan::call('openapi:generate');
});