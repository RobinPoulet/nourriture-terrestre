<?php

namespace App\Model;

use App\Database\Database;
use Exception;
use JsonSerializable;
use PDO;
use ReflectionClass;
use ReflectionException;
use stdClass;

class Order extends Model
{
    /** @var string Nom de la table en base */
    protected static string $table = 'orders';

    /** @var string[] Tableau des propriétés pouvant être directement mises à jour dans l'interface */
    protected static array $fillables = [
        'perso',
        'user_id',
        'creation_date',
        'modification_date',
    ];

    /**
     * Relation avec la table User
     *
     * @return ?User
     *
     * @throws Exception
     */
    public function user(): ?User
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec la table Dish
     *
     * @param bool $forceReload Forcer le rechargement de la relation ?
     *
     * @return Dish[]
     */
    public function dishes(bool $forceReload = false): array
    {
        return $this->belongsToMany(Dish::class, 'order_dishes', 'order_id', 'dish_id', $forceReload);
    }

    /**
     * Associe des plats à la commande
     *
     * @param array $dishes [dish_id => quantity]
     *
     * @return void
     */
    public function attachDishes(array $dishes): void
    {
        foreach ($dishes as $dishId => $quantity) {
            static::attachPivot('order_dishes', [
                'order_id' => $this->id,
                'dish_id'  => $dishId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Synchroniser des plats attachés à une commande
     *
     * @param array $dishes Tableau de plats
     *
     * @return void
     */
    public function syncDishes(array $dishes): void
    {
        static::syncPivot('order_dishes', ['order_id' => $this->id], $dishes, 'dish_id');
    }

    /**
     * Récupérer le nombre total de plat commandés par type de plat pour une date (en option)
     *
     * @param ?string $date Date de récupération pour les totaux
     *
     * @return array
     */
    public static function getDishTotalQuantityByDate(string $date = null): array
    {
        $returnValue = [];

        $date = $date ?? date('Y-m-d');

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

    /**
     * Extrait la propriété pivot et la retourne sous forme de tableau clé valeur
     *
     * @return array
     */
    public function extractPivotsToArray(): array
    {
        $returnValue = [];

        foreach ($this->dishes() as $dish) {

            if ($dish->pivot instanceof stdClass) {
                $returnValue[] = get_object_vars($dish->pivot);
            }
        }

        return $returnValue;
    }
}