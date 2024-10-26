<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Console\Command;
use AbdelrhmanMohamed2\Crudy\Traits\GenerateStub;
use AbdelrhmanMohamed2\Crudy\Services\FillableFields;

class MakeRequests extends Command
{
    use GenerateStub;

    protected $signature = 'crudy:make:requests {name}';
    protected $description = 'Generate a Store Request with validation rules';
    protected string $stubPath = __DIR__ . '/../stubs/Request.stub';

    public function handle()
    {
        $name = $this->argument('name');

        // Retrieve fillable rules
        $rules = FillableFields::getRulesAsString();

        $this->createStoreRequest($name, $rules);
        $this->createUpdateRequest($name, $rules);
    }

    private function createStoreRequest(string $name, string $rules): void
    {
        $result = $this->generate(
            stubKeys: [
                '{{ namespace }}',
                '{{ RequestName }}',
                '{{ rules }}'
            ],
            stubData: [
                'namespace' => config('crudy.namespaces.requests'). "\\$name",
                'RequestName' => "Store{$name}Request",
                'rules' => $rules
            ],
            dirPath: config('crudy.paths.requests') . '/' . $name,
            fileName: "Store{$name}Request" . '.php',
        );

        if (!$result) {
            $this->warn("Request already exists at: {$name}/Store{$name}Request" . '.php');
        }else{
            $this->info("Request created at: {$name}/Store{$name}Request" . '.php');
        }
    }

    private function createUpdateRequest(string $name, string $rules): void
    {
        $result = $this->generate(
            stubKeys: [
                '{{ namespace }}',
                '{{ RequestName }}',
                '{{ rules }}'
            ],
            stubData: [
                'namespace' => config('crudy.namespaces.requests') . "\\$name",
                'RequestName' => "Update{$name}Request",
                'rules' => $rules
            ],
            dirPath: config('crudy.paths.requests') . '/' . $name,
            fileName: "Update{$name}Request" . '.php',
        );

        if ($result) {
            $this->info("Request created at: {$name}/Update{$name}Request" . '.php');
        }else{
            $this->warn("Request already exists at: {$name}/Update{$name}Request" . '.php');
        }
    }
}
