<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\select;
use AbdelrhmanMohamed2\Crudy\Traits\GenerateStub;
use AbdelrhmanMohamed2\Crudy\Services\InputHelper;
use AbdelrhmanMohamed2\Crudy\Services\FillableFields;

class MakeModel extends Command
{
    use GenerateStub;

    protected $signature = 'crudy:make:model {name}';
    protected $description = 'Generate a model with interactive options for fillable fields, casts, and relationships';
    protected string $stubPath = __DIR__ . '/../stubs/Model.stub';

    public function handle()
    {
        $name = $this->argument('name');


        $this->askForFillable()
            ->askForRelationships()
            ->generateModel($name);
    }

    protected function generateModel($name): void
    {

        // Format fillable array
        $fillableArray = $this->getFillableFields();

        // Format casts array
        $castsArray = $this->getCasts();

        // Add relationships methods
        $relations = $this->getRelationshipsMethods();
        $relationshipsMethods = $relations['relationshipsMethods'];
        $importList = $relations['importList'];

        $result = $this->generate(
            stubKeys: [
                '{{ namespace }}',
                '{{ modelName }}',
                '{{ fillableFields }}',
                '{{ castsArray }}',
                '{{ relationshipsMethods }}',
                '{{ importList }}'
            ],
            stubData: [
                config('crudy.namespaces.models'),
                $name,
                $fillableArray,
                $castsArray,
                $relationshipsMethods,
                $importList
            ],
            dirPath: config('crudy.paths.models'),
            fileName: $name . '.php',
        );

        if ($result) {
            $this->info("Model created at: " . config('crudy.paths.models') . '/' . $name . '.php');
        } else {
            $this->warn("Model already exists at: " . config('crudy.paths.models') . '/' . $name . '.php');
        }
    }

    private function askForFillable(): self
    {
        while (true) {
            $fieldName = text('Enter field name for fillable (leave empty to finish):');

            if (empty($fieldName)) {
                break;
            }

            $type = select(
                label: 'Select the data type for ' . $fieldName,
                options: array_keys(InputHelper::$get),
                default: 'string',
                required: true
            );

            FillableFields::setFillable([
                'name' => $fieldName,
                'type' => $type
            ]);
        }

        return $this;
    }

    private function askForRelationships(): self
    {
        while (true) {
            $relationshipType = select(
                label: 'Select relationship type (or choose "none" to skip)',
                options: [
                    'hasOne',
                    'hasMany',
                    'belongsTo',
                    'belongsToMany',
                    'none'
                ],
                default: 'none',
                required: true
            );

            if ($relationshipType === 'none') {
                break;
            }

            $relatedModel = text('Enter the related model name for ' . $relationshipType);


            FillableFields::setRelationship([
                'relationshipType' => $relationshipType,
                'relatedModel' => $relatedModel,
                'methodName' => InputHelper::getRelationMethodName($relatedModel, $relationshipType)
            ]);
        }

        return $this;
    }

    private function getFillableFields(): string
    {
        $fillable = FillableFields::getFillable();

        return implode(",\n", array_map(fn($field) => "'{$field['name']}'", $fillable));
    }

    private function getCasts(): string
    {
        $casts = FillableFields::getCasts();

        return implode(",\n", array_map(fn($field) => "'{$field['name']}' => '{$field['type']}'", $casts));
    }

    private function getRelationshipsMethods(): array
    {
        $relationships = FillableFields::getRelationships();

        $relationshipsMethods = '';
        $importList = '';

        foreach ($relationships as $relationship) {
            $relatedModelClass = Str::studly($relationship['relatedModel']);
            $methodName = $relationship['methodName'];

            switch ($relationship['relationshipType']) {
                case 'hasOne':
                    $importList .= "use \Illuminate\Database\Eloquent\Relations\HasOne;\n\n";
                    $relationshipsMethods .= "public function {$methodName}(): HasOne\n {\n return \$this->hasOne({$relatedModelClass}::class); \n}\n\n";
                    break;

                case 'hasMany':
                    $importList .= "use \Illuminate\Database\Eloquent\Relations\HasMany;\n\n";
                    $relationshipsMethods .= "public function {$methodName}(): HasMany\n {\n return \$this->hasMany({$relatedModelClass}::class); \n}\n\n";
                    break;

                case 'belongsTo':
                    $importList .= "use \Illuminate\Database\Eloquent\Relations\BelongsTo;\n\n";
                    $relationshipsMethods .= "public function {$methodName}(): BelongsTo\n {\n return \$this->belongsTo({$relatedModelClass}::class); \n}\n\n";
                    break;

                case 'belongsToMany':
                    $importList .= "use \Illuminate\Database\Eloquent\Relations\BelongsToMany;\n\n";
                    $relationshipsMethods .= "public function {$methodName}(): BelongsToMany\n {\n return \$this->belongsToMany({$relatedModelClass}::class); \n}\n\n";
                    break;

                default:
                    break;
            }
        }

        return [
            "relationshipsMethods" => $relationshipsMethods,
            "importList" => $importList
        ];
    }
}
