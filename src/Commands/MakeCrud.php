<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Console\Command;

class MakeCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate CRUD operations for a specified model';

    public function handle()
    {
        $this->checkApiInstalled();

        $name = $this->argument('name');

        $this->info("Generating CRUD for model: $name");

        // Step 1: Generate the model
        $this->generateModel($name);

        // Step 2: Generate migration
        $this->generateMigration($name);

        // Step 3: Generate requests
        $this->generateRequests($name);

        // Step 4: Generate service
        $this->generateService($name);

        // Step 5: Generate resource
        $this->generateResource($name);

        // Step 6: Generate lang files
        $this->generateLangFiles($name);

        // Step 7: Generate controller
        $this->generateController($name);

        // Step 8: Generate routes
        $this->generateRoutes($name);

        $this->info("CRUD generation for $name completed successfully.");
    }

    protected function generateModel($name)
    {
        $this->call('crudy:make:model', [
            'name' => $name,
        ]);
    }

    protected function generateRequests($name)
    {
        $this->call('crudy:make:requests', [
            'name' => $name
        ]);
    }

    protected function generateService($name)
    {
        $this->call('crudy:make:service', [
            'name' => $name
        ]);
    }

    protected function generateMigration($name)
    {
        $this->call('crudy:make:migration', [
            'name' => $name,
        ]);
    }

    protected function generateResource($name)
    {
        $this->call('crudy:make:resource', [
            'name' => $name,
        ]);
    }

    protected function generateLangFiles($name)
    {
        $this->call('crudy:generate-lang', [
            'name' => $name,
        ]);
    }

    protected function generateController($name)
    {
        $this->call('crudy:make:controller', [
            'name' => $name,
        ]);
    }

    protected function generateRoutes($name)
    {
        $this->call('make:api-resource-route', [
            'name' => $name,
        ]);
    }

    private function checkApiInstalled()
    {
        if (!config('crudy.api_installed', false)) {
            $this->error('The API installation is required. Please run "php artisan install:api" first.');
            exit;
        }
    }
}
