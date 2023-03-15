<?php

namespace Ekirkaplan\LaravelBlueprintGenerator\Foundation;

class RequestRuleCreatorFoundation
{
    private array $compareRules = [];

    public function __construct()
    {
        $this->initCompareRules();
    }

    public function init(array $colm, string $tableName, array $fk = []): array
    {
        $rules = [];
        if ($fk['localColm'] == $colm['properties']['name']){
            $rules[] = "required";
            $rules[] = "int";
            $rules[] = "exist:{$fk['on']},{$fk['references']}";
            dd($rules);
        }
    }

    private function compare(string $type): string
    {
        return $this->compareRules[$type];
    }


    private function initCompareRules(): void
    {
        $this->compareRules = [
            "bigIncrements" => "int",
            "bigint" => "int",
            "binary" => "string",
            "boolean" => "bool",
            "char" => "string",
            "date" => "date",
            "dateTime" => "date_format:Y-m-d H:i:s",
            "dateTimeTz" => "date_format:Y-m-d H:i:s T",
            "decimal" => "numeric",
            "double" => "numeric",
            "enum" => "string",
            "float" => "numeric",
            "geometry" => "string",
            "geometryCollection" => "string",
            "increments" => "int",
            "integer" => "int",
            "ipAddress" => "string",
            "json" => "array",
            "jsonb" => "array",
            "lineString" => "string",
            "longText" => "string",
            "macAddress" => "string",
            "mediumIncrements" => "int",
            "mediumInteger" => "int",
            "mediumText" => "string",
            "morphs" => "string",
            "uuidMorphs" => "string",
            "multiLineString" => "string",
            "multiPoint" => "string",
            "multiPolygon" => "string",
            "nullableMorphs" => "string",
            "nullableUuidMorphs" => "string",
            "nullableTimestamps" => "date",
            "point" => "string",
            "polygon" => "string",
            "rememberToken" => "string",
            "set" => "string",
            "smallIncrements" => "int",
            "smallInteger" => "int",
            "softDeletes" => "date",
            "softDeletesTz" => "date",
            "string" => "string",
            "text" => "string",
            "time" => "date_format:H:i:s",
            "timeTz" => "date_format:H:i:s T",
            "timestamp" => "date_format:Y-m-d H:i:s",
            "timestampTz" => "date_format:Y-m-d H:i:s T",
            "timestamps" => "date_format:Y-m-d H:i:s",
            "timestampsTz" => "date_format:Y-m-d H:i:s T",
            "tinyIncrements" => "int",
            "tinyInteger" => "int",
            "unsignedBigInteger" => "int",
            "unsignedDecimal" => "numeric",
            "unsignedInteger" => "int",
            "unsignedMediumInteger" => "int",
            "unsignedSmallInteger" => "int",
            "unsignedTinyInteger" => "int",
            "uuid" => "string",
            "year" => "int",
        ];
    }
}
