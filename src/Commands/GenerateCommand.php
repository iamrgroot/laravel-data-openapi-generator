<?php

declare(strict_types=1);

namespace IAmRGroot\LaravelData\OpenApi\Commands;

use IAmRGroot\LaravelData\OpenApi\Data\RouteData;
use IAmRGroot\LaravelData\OpenApi\Support\RouteDataExtracter;
use IAmRGroot\LaravelData\OpenApi\Support\RouteFinder;
use Illuminate\Console\Command;

class GenerateCommand extends Command
{
    protected $signature = 'openapi:generate';

    protected $description = 'Generates OpenAPI specification from routes and data objects';
    
    public function __construct(
        private RouteFinder $routeFinder,
        private RouteDataExtracter $routeDataExtracter,
    ){ 
        parent::__construct();
    }

    public function handle(): int
    {
        $routes = $this->routeFinder->find();

        $data = $this->routeDataExtracter->extractCollection($routes);

        $refs = array_values(array_unique(array_map(
            fn(RouteData $routeData) => $routeData->responseType->type,
            $data,
        )));

        dump($data, $refs);


        return Command::SUCCESS;
    }
}