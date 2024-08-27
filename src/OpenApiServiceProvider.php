<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi;

use IAmRGroot\LaravelData\OpenApi\Commands\GenerateCommand;
use Illuminate\Support\ServiceProvider;

class OpenApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCommand::class,
            ]);
        }
    }

    public function register(): void
    {
    }
}