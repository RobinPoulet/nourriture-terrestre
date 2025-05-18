<?php

namespace App\Core;

use App\Database\Database;
use PDO;

class QueryBuilder
{
    protected array $wheres = [];
    protected array $bindings = [];
    protected array $orderBys = [];
    protected ?int $limit = null;

    public function __construct(protected string $table, protected string $modelClass)
    {}

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

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
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBys[] = [$column, $direction];
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

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

        return array_map(function ($attributes) {
            $model = new $this->modelClass();
            $model->fillFromArray($attributes);

            return $model;
        }, $results);
    }


    public function first(): ?Object
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }
}
