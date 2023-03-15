<?php

namespace Ekirkaplan\LaravelBlueprintGenerator\Services;

use Ekirkaplan\LaravelBlueprintGenerator\Foundation\MigrationReaderFoundation;
use Ekirkaplan\LaravelBlueprintGenerator\Foundation\RequestRuleCreatorFoundation;
use Illuminate\Support\Str;

class FormRequestService
{

    private array $tables = [];
    private array $rules = [];


    public function __construct()
    {
        $this->getAllTable();
    }

    public function init()
    {
        foreach ($this->tables as $table => $colms) {
            $this->makeFolder($table);
            $this->createStoreRequest($colms, $table);
        }
    }

    private function createStoreRequest(array $colms, string $tableName)
    {
        foreach ($colms as $colmName => $colm) {

            if ($colmName == "id") {
                continue;
            }

            $this->ruleCreator($colm, $tableName);
        }
    }

    private function ruleCreator(array $colm, string $tableName)
    {

        $type = $colm['type'];
        $name = $colm['properties']['name'];
        $nullable = $colm['properties']['notnull'];

        $foreingKeys = $this->foreignKeyProcess($name, $tableName);

        $creatorFoundation = new RequestRuleCreatorFoundation();
        $rules = $creatorFoundation->init($colm, $tableName, $foreingKeys);


        $rules[$name] = [];

        dd($colm);
    }

    private function foreignKeyProcess(string $colmName, string $tableName): bool|array
    {
        $foringkeys = $this->tables[$tableName]['foreign_key'];
        if (count($foringkeys) < 1) {
            return false;
        }

        foreach ($foringkeys as $foringkey) {
            if ($foringkey['localColm'] == $colmName) {
                return $foringkey;
            }
        }
    }

    private function makeFolder(string $tableName): void
    {
        $path = app_path("Http/Requests");

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $studlyCaseSingular = Str::singular(Str::studly($tableName));

        if (!is_dir($path."/{$studlyCaseSingular}")) {
            mkdir($path."/{$studlyCaseSingular}", 0755, true);
        }
    }

    private function getAllTable(): void
    {
        $migrationReaderFoundation = new MigrationReaderFoundation();
        $this->tables = $migrationReaderFoundation->init();
    }
}
