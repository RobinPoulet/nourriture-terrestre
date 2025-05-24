<?php

namespace App\Model;

use App\Core\QueryBuilder;
use App\Database\Database;
use Exception;
use PDO;

abstract class BaseModel
{
    protected ?int $id = null;
    protected ?string $creation_date = null;
    protected ?string $modification_date = null;
    protected array $loadedRelations = [];
    protected ?object $pivot = null;

    public function __get(string $key)
    {
        return ($this->$key ?? null);
    }

    public function __set(string $key, $value): void
    {
        $this->$key = $value;
    }

    /**
     * @param class-string<BaseModel> $relatedClass
     * @param string                  $foreignKey
     * @return array
     * @throws Exception
     */
    public function hasMany(string $relatedClass, string $foreignKey): array
    {
        $relatedTable = $relatedClass::getTable();
        $localValue = $this->id;
        return (new QueryBuilder($relatedTable, $relatedClass))
            ->where($foreignKey, '=', $localValue)
            ->get();
    }

    /**
     * @param class-string<BaseModel> $relatedClass $relatedClass
     * @param string                  $foreignKey
     * @return mixed|null
     * @throws Exception
     */
    public function belongsTo(string $relatedClass, string $foreignKey): ?object
    {
        $relatedTable = $relatedClass::getTable();
        $relatedKey = $relatedClass::getPrimaryKey();
        $foreignValue = $this->$foreignKey; // ex: $this->user_id

        return (new QueryBuilder($relatedTable, $relatedClass))
            ->where($relatedKey, '=', $foreignValue) // ✅ on filtre sur id du user
            ->first();
    }

    public function belongsToMany(string $relatedClass, string $pivotTable, string $foreignKey, string $relatedKey, bool $forceReload = false): array
    {
        $cacheKey = "btm_{$pivotTable}_{$relatedClass}";

        if (!$forceReload && isset($this->loadedRelations[$cacheKey])) {
            return $this->loadedRelations[$cacheKey];
        }

        $relatedTable = $relatedClass::getTable();

        $query = "
            SELECT {$relatedTable}.*, {$pivotTable}.*
            FROM {$relatedTable}
            JOIN {$pivotTable} ON {$pivotTable}.{$relatedKey} = {$relatedTable}.id
            WHERE {$pivotTable}.{$foreignKey} = ?
        ";

        $stmt = Database::getInstance()->prepare($query);
        $stmt->execute([$this->id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $instances = array_map(function ($data) use ($relatedClass, $foreignKey, $relatedKey) {
            $modelData = [];
            $pivotData = [];

            foreach ($data as $key => $value) {
                $normalizedKey = strtolower($key);

                if (in_array($normalizedKey, [$foreignKey, $relatedKey], true)) {
                    continue;
                }

                if (property_exists($relatedClass, $normalizedKey)) {
                    $modelData[$normalizedKey] = $value;
                } else {
                    $pivotData[$normalizedKey] = $value;
                }
            }

            $instance = new $relatedClass();
            $instance->fillFromArray($modelData);
            $instance->pivot = (object) $pivotData;

            return $instance;
        }, $results);

        $this->loadedRelations[$cacheKey] = $instances;

        return $instances;
    }

    public static function getPrimaryKey(): string
    {
        return 'id';
    }


    /**
     * @throws Exception
     */
    public static function getTable(): string
    {
        throw new Exception('You must implement getTable in subclass');
    }

    // Connection DB simulée
    protected static function db(): PDO
    {
        return Database::getInstance(); // adapte selon ton projet
    }

}