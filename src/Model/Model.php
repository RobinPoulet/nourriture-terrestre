<?php

namespace App\Model;

use App\Core\QueryBuilder;
use App\Database\Database;

abstract class Model extends BaseModel
{
    /** @var string Nom de la table en base */
    protected static string $table;

    /** @var string[] Tableau des propriétés pouvant être directement mises à jour dans l'interface */
    protected static array $fillables = [];

    /**
     * Faire une query en base
     *
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::$table, static::class);
    }

    /**
     * Retourne tous les éléments d'une table
     *
     * @param string $orderBy Order by à appliquer sur la requête
     *
     * @return array
     */
    public static function all(string $orderBy = ''): array
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

    /**
     * Trouver un élément dans une table
     *
     * @param int $id Id de l'élément à trouver
     *
     * @return ?Object
     */
    public static function find(int $id): ?Object
    {
        return static::query()
            ->where('id', $id)
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

    /**
     * Créer un élément en base
     *
     * @param array $data Donnée de l'élément à créer
     *
     * @return ?Object
     */
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

    /**
     * Mise à jour d'un élément en base de donnée
     *
     * @param int   $id   Id de l'élément à update
     * @param array $data Données à mettre à jour
     *
     * @return ?Object
     */
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

    /**
     * Supprimer en base de données
     *
     * @param int $id Id de l'élément à supprimer
     *
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = ?";
        $stmt = Database::getInstance()->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Attache à une table pivot
     *
     * @param string $pivotTable Nom de la table pivot
     * @param array  $data       Données à rattacher
     *
     * @return void
     */
    public static function attachPivot(string $pivotTable, array $data): void
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $pivotTable ($columns) VALUES ($placeholders)";
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute(array_values($data));
    }

    /**
     * Sync une table pivot(détache puis rattache)
     *
     * @param string $pivotTable Nom de la table pivot
     * @param array  $where      Conditions du where
     * @param array  $items      Nouveaux éléments
     * @param string $keyName    Nom de la clé
     *
     * @return void
     */
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

    /**
     * Remplits les propriétés d'un model à partir d'un tableau d'atttributs
     *
     * @param array $attributes Tableau d'attributs
     *
     * @return void
     */
    public function fillFromArray(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->{strtolower($key)} = $value;
        }
    }
}