<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRoute extends Command
{
    protected $signature = 'make:api-resource-route {name}';
    protected $description = 'Create an API resource route and append it to crud.php';

    public function handle()
    {
        $name = $this->argument('name');

        // Define the route string
        $routeName = Str::snake(Str::plural($name));
        $controllerNamespace = config('crudy.namespaces.controllers') . '\\'. $name . 'Controller::class';
        $routeString = "Route::apiResource('{$routeName}', {$controllerNamespace});";

        $crudFilePath = base_path(config('crudy.paths.routes'));

        // Check if the crud.php file exists
        if (!File::exists($crudFilePath)) {
            File::put($crudFilePath, "<?php\n\n use Illuminate\Support\Facades\Route;\n\n");
        }

        // Append the route to the crud.php file
        if (File::append($crudFilePath, "\n" . $routeString)) {
            $this->info("API resource route created at: " . config('crudy.paths.routes'));
        } else {
            $this->warn("Failed to append route to " . config('crudy.paths.routes') . ".");
        }
    }
}
