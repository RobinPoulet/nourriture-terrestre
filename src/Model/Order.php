<?php

namespace App\Model;

use App\Database\Database;
use Exception;
use JsonSerializable;
use PDO;

class Order extends Model implements JsonSerializable
{
    protected static string $table = "orders";
    protected string $perso;
    protected int $user_id;

    protected static array $fillables = [
        "perso",
        "user_id",
        "creation_date",
        "modification_date",
    ];

    /**
     * @throws Exception
     */
    public function user(): ?User
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dishes(bool $forceReload = false): array
    {
        return $this->belongsToMany(Dish::class, 'order_dishes', 'order_id', 'dish_id', $forceReload);
    }

    /**
     * Associe des plats à la commande
     *
     * @param array $dishes [dish_id => quantity]
     */
    public function attachDishes(array $dishes): void
    {
        foreach ($dishes as $dishId => $quantity) {
            static::attachPivot("order_dishes", [
                "order_id" => $this->id,
                "dish_id"  => $dishId,
                "quantity" => $quantity
            ]);
        }
    }

    public function syncDishes(array $dishes): void
    {
        static::syncPivot("order_dishes", ["order_id" => $this->id], $dishes, "dish_id");
    }

    public static function getDishTotalQuantityByDate(string $date = null): array
    {
        $returnValue = [];

        $date = $date ?? date("Y-m-d");

            $query = "
            SELECT od.dish_id AS dish_id, SUM(od.quantity) AS total_quantity
            FROM order_dishes od
            JOIN orders o ON od.order_id = o.id
            WHERE o.creation_date = :date
            GROUP BY od.dish_id
            ORDER BY od.dish_id
        ";

        $stmt = Database::getInstance()->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $item) {
            $returnValue[(int) $item['dish_id']] = (int) $item['total_quantity'];
        }

        return $returnValue;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'                => $this->id,
            'creation_date'     => $this->creation_date,
            'modification_date' => $this->modification_date,
            'perso'             => $this->perso,
            'user_id'           => $this->user_id,
        ];
    }
}