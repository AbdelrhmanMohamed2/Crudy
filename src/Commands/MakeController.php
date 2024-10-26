<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use AbdelrhmanMohamed2\Crudy\Traits\GenerateStub;

class MakeController extends Command
{
    use GenerateStub;

    protected $signature = 'crudy:make:controller {name}';
    protected $description = 'Generate a controller file.';
    protected string $stubPath = __DIR__ . '/../stubs/Controller.stub';

    public function handle()
    {
        $name = $this->argument('name');

        $result = $this->generate(
            stubKeys: [
                '{{ namespace }}',
                '{{ imports }}',
                '{{ name }}',
                '{{ camelName }}',
                '{{ snakeName }}',
            ],
            stubData: [
                config('crudy.namespaces.controllers'),
                $this->getImports($name),
                $name,
                Str::camel($name),
                Str::snake($name),
            ],
            dirPath: config('crudy.paths.controllers'),
            fileName: "{$name}Controller.php",
        );

        $controllerPath = config('crudy.paths.controllers') . "/{$name}Controller.php";

        if ($result) {
            $this->info("Controller created at: {$controllerPath}");
        } else {
            $this->warn("Controller already exists at: {$controllerPath}");
        }
    }

    private function getImports(string $name): string
    {
        $importsArray = [
            'use App\Http\Controllers\Controller;',
            'use ' . config('crudy.namespaces.requests') . '\\' . $name . '\\Store' . $name . 'Request;',
            'use ' . config('crudy.namespaces.requests') . '\\' . $name . '\\Update' . $name . 'Request;',
            'use ' . config('crudy.namespaces.resources') . '\\' . $name . 'Resource;',
            'use ' . config('crudy.namespaces.models') . '\\' . $name . ';',
            'use ' . config('crudy.namespaces.services') . '\\' . $name . 'Service;',
        ];

        return implode("\n", $importsArray);
    }
}
