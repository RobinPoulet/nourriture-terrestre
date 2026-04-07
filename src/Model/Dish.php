<?php

namespace App\Model;

use Exception;
use ReflectionClass;
use ReflectionException;
use stdClass;

class Dish extends Model
{
    /** @var string Nom de la table en base */
    protected static string $table = 'dishes';

    /** @var string[] Tableau des propriétés pouvant être directement mises à jour dans l'interface */
    protected static array $fillables = [
        'name',
        'total',
        'menu_id',
        'creation_date',
        'modification_date',
    ];

    /**
     * Relation avec la table order (un plat peut-être associé à plusieurs orders)
     *
     * @return Order[]
     */
    public function orders(): array
    {
        return $this->belongsToMany(Order::class, 'order_dishes', 'dish_id', 'order_id');
    }

    /**
     * Relation avec la table menu (un plat appartient à un seul menu)
     *
     * @return ?Menu
     *
     * @throws Exception
     */
    public function menu(): ?Menu
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

}