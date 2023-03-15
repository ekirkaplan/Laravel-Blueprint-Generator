<?php

namespace Ekirkaplan\LaravelBlueprintGenerator\Foundation;

use Illuminate\Support\Facades\Schema;

class MigrationReaderFoundation
{
    private $migrator;
    protected $connection;


    public function __construct()
    {
        $this->migrator = app('migrator');
        $this->getConnection();
    }

    public function init(): array
    {
        $tables = $this->getAllTable();

        return $this->fetchTableWithColums($tables);
    }

    protected function getConnection(): void
    {
        $this->connection = \DB::connection();
    }

    protected function fetchTableWithColums(array $tables)
    {
        $columns = [];

        foreach ($tables as $table) {
            $tableDetails = $this->connection->getDoctrineSchemaManager()->listTableDetails($table);

            $columns[$table] = [];

            foreach ($tableDetails->getColumns() as $column) {
                $columnType = $column->getType()->getName();
                $columnName = $column->getName();
                $columnProperties = $column->toArray();

                $columns[$table][$columnName] = [
                    'type' => $columnType,
                    'properties' => $columnProperties,
                ];
            }
        }

        return $columns;
    }

    protected function getAllTable(): array
    {
        $migrator = $this->migrator;

        $connection = $this->connection;

        return $connection->getDoctrineSchemaManager()->listTableNames();
    }
}
