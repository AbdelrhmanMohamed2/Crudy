<?php

namespace AbdelrhmanMohamed2\Crudy\Services;

use Illuminate\Support\Str;

class InputHelper
{
    public static array $get = [
        'string' => [
            'rules' => ['required', 'string', 'max:255'],
            'migration' => 'string',
        ],
        'text' => [
            'rules' => ['required', 'string'],
            'migration' => 'text',
        ],
        'integer' => [
            'rules' => ['required', 'integer'],
            'migration' => 'integer',
        ],
        'boolean' => [
            'rules' => ['required', 'boolean'],
            'migration' => 'boolean',
        ],
        'float' => [
            'rules' => ['required', 'numeric'],
            'migration' => 'float',
        ],
        'date' => [
            'rules' => ['required', 'date'],
            'migration' => 'date',
        ],
        'datetime' => [
            'rules' => ['required', 'date'],
            'migration' => 'datetime',
        ],
        'json' => [
            'rules' => ['required', 'json'],
            'migration' => 'json',
        ],
        'id' => [
            'rules' => ['required', 'integer', 'exists:your_table,id'],
            'migration' => 'foreignId',
        ],
    ];

    public static function getRelationMethodName(string $model, string $relationType): string
    {
        $methodName = Str::camel($model);

        if (in_array($relationType, ['hasMany', 'belongsToMany'])) {
            return Str::plural($methodName);
        }

        return $methodName;
    }
}
