<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use AbdelrhmanMohamed2\Crudy\Traits\GenerateStub;
use AbdelrhmanMohamed2\Crudy\Services\FillableFields;

class MakeMigration extends Command
{
    use GenerateStub;

    protected $signature = 'crudy:make:migration {name}';
    protected $description = 'Generate a migration file.';
    protected string $stubPath = __DIR__ . '/../stubs/Migration.stub';

    public function handle()
    {
        $name = $this->argument('name');

        $table = Str::plural(Str::snake($name));
        $timestamp = date('Y_m_d_His');
        $migrationName = "create_{$table}_table";
        $migrationPath = config('crudy.paths.migrations') . "/{$timestamp}_{$migrationName}.php";

        $result = $this->generate(
            stubKeys: [
                '{{ tableName }}',
                '{{ columns }}',
            ],
            stubData: [
                $table,
                FillableFields::getColumnsAsString(),
            ],
            dirPath: config('crudy.paths.migrations'),
            fileName: "{$timestamp}_{$migrationName}.php",
        );

        if ($result) {
            $this->info("Migration created at: {$migrationPath}");
        } else {
            $this->warn("Migration already exists at: {$migrationPath}");
        }
    }
}
