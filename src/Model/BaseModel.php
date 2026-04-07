<?php

namespace App\Model;

use App\Core\QueryBuilder;
use App\Database\Database;
use Exception;
use PDO;

abstract class BaseModel
{
    /** @var array Relations chargés */
    protected array $loadedRelations = [];

    /** @var ?object Pivot entre 2 tables */
    protected ?object $pivot = null;

    /** @var array Tableau d'attributs du model */
    protected array $attributes = [];

    /**
     * Getter (magique)
     *
     * @param string $key
     *
     * @return array|mixed|object|null
     */
    public function __get(string $key)
    {
        $returnValue = null;

        if (isset($this->attributes[$key])) {
            $returnValue = $this->attributes[$key];
        }

        if (isset($this->loadedRelations[$key])) {
            $returnValue = $this->loadedRelations[$key];
        }

        if (isset($this->pivot->$key)) {
            $returnValue = $this->pivot->$key;
        }

        if ($key === 'pivot') {
            $returnValue = $this->pivot;
        }

        if ($key === 'loadedRelations') {
            $returnValue = $this->loadedRelations;
        }

        return $returnValue;
    }

    /**
     * Setter (magique))
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        } else {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Récupérer la table de la classe
     *
     * @return string
     */
    public static function getTable(): string
    {
        return static::$table
            ?? strtolower((new \ReflectionClass(static::class))->getShortName()) . 's';
    }

    /**
     * Relation 1-1
     *
     * @param class-string<BaseModel> $relatedClass Classe cible de la relation
     * @param string                  $foreignKey   Clé étrangère
     *
     * @return ?object
     *
     * @throws Exception
     */
    public function hasOne(string $relatedClass, string $foreignKey): ?object
    {
        $relatedTable = $relatedClass::getTable();
        $localValue = $this->id;
        return (new QueryBuilder($relatedTable, $relatedClass))
            ->where($foreignKey, $localValue)
            ->first();
    }

    /**
     * Relation 1-n
     *
     * @param class-string<BaseModel> $relatedClass Classe cible de la relation
     * @param string                  $foreignKey   Clé étrangère
     *
     * @return array
     *
     * @throws Exception
     */
    public function hasMany(string $relatedClass, string $foreignKey): array
    {
        $relatedTable = $relatedClass::getTable();
        $localValue = $this->id;
        return (new QueryBuilder($relatedTable, $relatedClass))
            ->where($foreignKey, $localValue)
            ->get();
    }

    /**
     * Relation n-1
     *
     * @param class-string<BaseModel> $relatedClass Classe cible de la relation
     * @param string                  $foreignKey   Clé étrangère
     * @return ?object
     *
     * @throws Exception
     */
    public function belongsTo(string $relatedClass, string $foreignKey): ?object
    {
        $relatedTable = $relatedClass::getTable();
        $relatedKey = $relatedClass::getPrimaryKey();
        $foreignValue = $this->$foreignKey; // ex: $this->user_id

        return (new QueryBuilder($relatedTable, $relatedClass))
            ->where($relatedKey, $foreignValue) // ✅ on filtre sur id du user
            ->first();
    }

    /**
     * Relation n-n
     *
     * @param string $relatedClass Classe cible de la relation
     * @param string $pivotTable   Nom de la table pivot
     * @param string $foreignKey   Clé étrangère
     * @param string $relatedKey   Clé cible
     * @param bool   $forceReload  Forcer le rechargement ?
     *
     * @return array
     */
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

    /**
     * Retourne la clé principale
     *
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return 'id';
    }
}