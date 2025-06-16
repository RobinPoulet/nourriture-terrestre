<?php

namespace App\Core;

use App\Database\Database;
use PDO;

class QueryBuilder
{
    /** @var array Tableau des clauses where de la requête */
    protected array $wheres = [];

    /** @var array Tableau des bindings de la requête */
    protected array $bindings = [];

    /** @var array Tableau des order bys de la requête */
    protected array $orderBys = [];

    /** @var ?int Limit de la requête */
    protected ?int $limit = null;

    /**
     * Constructeur de la classe
     *
     * @param string $table      Nom de la table
     * @param string $modelClass Nom de la classe du Model
     */
    public function __construct(private readonly string $table, private readonly string $modelClass)
    {}

    /**
     * Ajouter un WHERE IN à la requête
     *
     * @param string $column Colonne
     * @param array  $values Valeurs
     *
     * @return self
     */
    public function whereIn(string $column, array $values): self
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $this->wheres[] = "$column IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, $values);

        return $this;
    }

    /**
     * Ajouter une condition ORDER BY à la requête.
     *
     * @param string $column    Colonne
     * @param string $direction Direction de l'order by
     *
     * @return self
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBys[] = [$column, $direction];
        return $this;
    }

    /**
     * Construction du where de la requête
     *
     * @param string $column   Colonne
     * @param mixed  $value    Valeur
     * @param string $operator Operateur
     *
     * @return self
     */
    public function where(string $column, mixed $value, string $operator = '='): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;

        return $this;
    }

    /**
     * Construction de la limite de la requête
     *
     * @param int $limit Limite de résultat de la requête
     *
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Retourne un tableau d'objet Model
     *
     * @return array
     */
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        if (!empty($this->orderBys)) {
            $sql .= ' ORDER BY ' . implode(', ', array_map(fn($o) => "{$o[0]} {$o[1]}", $this->orderBys));
        }
        if ($this->limit) {
            $sql .= " LIMIT {$this->limit}";
        }

        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute($this->bindings);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->mapDbResults($results);
    }

    /**
     * Retourne le premier élément d'une requête
     *
     * @return ?Object
     */
    public function first(): ?Object
    {
        $this->limit(1);
        $results = $this->get();

        return $results[0] ?? null;
    }

    /**
     * Map les résultats de la base en objet Model
     *
     * @param array $results Tableau de results de la base de données
     *
     * @return array Tableau de Model
     */
    private function mapDbResults(array $results): array
    {
        return array_map(function ($attributes) {
            $model = new $this->modelClass();
            $model->fillFromArray($attributes);

            return $model;
        }, $results);
    }
}
