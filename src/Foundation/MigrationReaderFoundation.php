<?php

namespace Ekirkaplan\LaravelBlueprintGenerator\Foundation;

use Illuminate\Support\Facades\Schema;

class MigrationReaderFoundation
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Database\Migrations\Migrator|(\Illuminate\Database\Migrations\Migrator&\Illuminate\Contracts\Foundation\Application)|\Illuminate\Foundation\Application|mixed
     */
    private $migrator;
    /**
     * @var
     */
    protected $connection;

    public function __construct()
    {
        $this->migrator = app('migrator');
        $this->getConnection();
    }

    /**
     * @return array
     */
    public function init(): array
    {
        $tables = $this->getAllTable();

        return $this->fetchTableWithColums($tables);
    }

    /**
     * @return void
     */
    protected function getConnection(): void
    {
        $this->connection = \DB::connection();
    }

    /**
     * @param  array  $tables
     * @return array
     */
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

            $foreignKeys = $tableDetails->getForeignKeys();

            foreach ($foreignKeys as $foreignKey) {

                $localColumns = $foreignKey->getLocalColumns();
                $foreignColumns = $foreignKey->getForeignColumns();
                $foreignTable = $foreignKey->getForeignTableName();
                $foreignKeyName = $foreignKey->getName();

                $columns[$table]['foreign_key'][$foreignKeyName] = [
                    'on' => $foreignTable,
                    'localColm' => $localColumns[0],
                    'references' => $foreignColumns[0],
                ];
            }

        }

        return $columns;
    }

    /**
     * @return array
     */
    protected function getAllTable(): array
    {
        $migrator = $this->migrator;

        $connection = $this->connection;

        return $connection->getDoctrineSchemaManager()->listTableNames();
    }
}
