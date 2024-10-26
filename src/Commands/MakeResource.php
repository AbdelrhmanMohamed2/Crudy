<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Console\Command;
use AbdelrhmanMohamed2\Crudy\Traits\GenerateStub;
use AbdelrhmanMohamed2\Crudy\Services\FillableFields;

class MakeResource extends Command
{
    use GenerateStub;

    protected $signature = 'crudy:make:resource {name}';
    protected $description = 'Generate a resource file.';
    protected string $stubPath = __DIR__ . '/../stubs/Resource.stub';

    public function handle()
    {
        $name = $this->argument('name');

        $result = $this->generate(
            stubKeys: [
                '{{ namespace }}',
                '{{ name }}',
                '{{ fields }}',
            ],
            stubData: [
                config('crudy.namespaces.resources'),
                $name,
                FillableFields::getFieldsAsString(),
            ],
            dirPath: config('crudy.paths.resources'),
            fileName: "{$name}Resource.php",
        );

        $resourcePath = config('crudy.paths.resources') . "/{$name}Resource.php";

        if ($result) {
            $this->info("Resource created at: {$resourcePath}");
        } else {
            $this->warn("Resource already exists at: {$resourcePath}");
        }
    }
}
