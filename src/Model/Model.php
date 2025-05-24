<?php

namespace App\Model;

use App\Core\QueryBuilder;
use App\Database\Database;
use PDO;

abstract class Model extends BaseModel
{
    protected static string $table;
    protected static array $fillables = [];

    public static function getTable(): string
    {
        return static::$table
            ?? strtolower((new \ReflectionClass(static::class))->getShortName()) . 's';
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::$table, static::class);
    }

    public static function all($orderBy = ''): array
    {
        $returnValue = [];

        if (!empty($orderBy)) {
            $returnValue = static::query()
                ->orderBy($orderBy)
                ->get();
        } else {
            $returnValue = static::query()->get();
        }

        return $returnValue;
    }

    public static function find(int $id): ?Object
    {
        return static::query()
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Récupère le dernier élément de la table (en fonction de la date de création).
     *
     * @return ?Object
     */
    public static function last(): ?Object
    {
        // Effectuer une requête pour récupérer le dernier élément, trié par ID décroissant
        return static::query()
            ->orderBy('id', 'desc')
            ->first();
    }

    public static function create(array $data): ?Object
    {
        $fields = array_intersect_key($data, array_flip(static::$fillables));
        $columns = implode(',', array_keys($fields));
        $placeholders = implode(',', array_fill(0, count($fields), '?'));

        $sql = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute(array_values($fields));
        $id = Database::getInstance()->lastInsertId();

        return static::find($id);
    }

    public static function update(int $id, array $data): ?Object
    {
        $returnValue = null;

        $fields = array_intersect_key($data, array_flip(static::$fillables));
        $sets = implode(', ', array_map(fn($key) => "$key = ?", array_keys($fields)));

        $sql = "UPDATE " . static::$table . " SET $sets WHERE id = ?";
        $stmt = Database::getInstance()->prepare($sql);

        if ($stmt->execute([...array_values($fields), $id])) {
            $returnValue = static::find($id);
        }


        return $returnValue;
    }

    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = ?";
        $stmt = Database::getInstance()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function attachPivot(string $pivotTable, array $data): void
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $pivotTable ($columns) VALUES ($placeholders)";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute(array_values($data));
    }

    public static function syncPivot(string $pivotTable, array $where, array $items, string $keyName): void
    {
        // Supprimer les anciens
        $whereClause = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($where)));
        $sql = "DELETE FROM $pivotTable WHERE $whereClause";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute(array_values($where));

        // Réinsérer les nouveaux
        foreach ($items as $keyValue => $extra) {
            $data = array_merge($where, [$keyName => $keyValue], is_array($extra) ? $extra : []);
            static::attachPivot($pivotTable, $data);
        }
    }

    public function fillFromArray(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->{strtolower($key)} = $value;
        }
    }


    public function toArray(): array
    {
        return $this->attributes;
    }
}