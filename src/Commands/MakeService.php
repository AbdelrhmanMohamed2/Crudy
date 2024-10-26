<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use AbdelrhmanMohamed2\Crudy\Traits\GenerateStub;

class MakeService extends Command
{
    use GenerateStub;

    protected $signature = 'crudy:make:service {name}';
    protected $description = 'Generate a service class.';
    protected string $stubPath = __DIR__ . '/../stubs/Service.stub';

    public function handle()
    {
        $name = $this->argument('name');

        $result = $this->generate(
            stubKeys: [
                '{{ namespace }}',
                '{{ modelNamespace }}',
                '{{ modelName }}',
                '{{ camelModelName }}',
            ],
            stubData: [
                config('crudy.namespaces.services'),
                config('crudy.namespaces.models') . '\\' . $name,
                $name,
                Str::camel($name),
            ],
            dirPath: config('crudy.paths.services'),
            fileName: $name . 'Service.php',
        );

        $servicePath = config('crudy.paths.services') . '/' . $name . 'Service.php';

        if ($result) {
            $this->info("Service created at: {$servicePath}");
        } else {
            $this->warn("Service already exists at: {$servicePath}");
        }
    }
}
