<?php

namespace AbdelrhmanMohamed2\Crudy\Services;

class FillableFields
{
    protected static array $fillable = [];
    protected static array $casts = [];
    protected static array $relationships = [];
    protected static array $rules = [];
    protected static array $migrationColumns = [];

    public static function setFillable(array $fillable): void
    {
        self::$fillable[] = $fillable;

        self::processFillableField($fillable);
    }

    public static function getFillable(): array
    {
        return self::$fillable;
    }

    public static function setCast(array $casts): void
    {
        self::$casts[] = $casts;
    }

    public static function getCasts(): array
    {
        return self::$casts;
    }

    public static function setRelationship(array $relationships): void
    {
        self::$relationships[] = $relationships;
    }

    public static function getRelationships(): array
    {
        return self::$relationships;
    }

    public static function setRules(string $inputName, array $rules): void
    {
        self::$rules[$inputName] = $rules;
    }

    public static function getRules(): array
    {
        return self::$rules;
    }

    public static function setMigrationColumns(string $inputName, string $column): void
    {
        self::$migrationColumns[$inputName] = $column;
    }

    public static function getMigrationColumns(): array
    {
        return self::$migrationColumns;
    }

    public static function getRulesAsString(): string
    {
        $output = [];

        foreach (self::$rules as $key => $value) {
            $output[] = "'{$key}' => ['" . implode("', '", $value) . "']";
        }

        return implode(",\n", $output);
    }

    public static function getColumnsAsString(): string
    {
        $output = [];

        foreach (self::getMigrationColumns() as $column => $type) {
            $output[] = '$table->' . $type . "('" . $column . "');";
        }

        return implode("\n", $output);
    }

    public static function getFieldsAsString(): string
    {
        $output = [];

        foreach (self::getFillable() as $column) {
            $output[] = "'{$column['name']}' => " . '$this->' . $column['name'];
        }

        foreach(self::getRelationships() as $relationship) {
            $methodName = $relationship['methodName'];

            $output[] = "'{$methodName}' => " . '$this->whenLoaded(' . "'{$methodName}'" . ')';
        }

        return implode(",\n", $output);
    }

    public static function processFillableField(array $fillable): void
    {
        if (!in_array($fillable['type'], [
            'string',
            'text',
            'id',
        ])) {

            FillableFields::setCast([
                'name' => $fillable['name'],
                'type' => $fillable['type']
            ]);
        }

        self::setRules($fillable['name'], InputHelper::$get[$fillable['type']]['rules']);

        self::setMigrationColumns($fillable['name'], InputHelper::$get[$fillable['type']]['migration']);
    }
}
