<?php

namespace App\Model;

use App\Model\Model;
use Exception;
use JsonSerializable;

class Dish extends Model implements JsonSerializable
{
    protected static string $table = 'dishes';

    protected string $name;
    protected int $total;
    protected int $menu_id;
    protected static array $fillables = [
        'name',
        'total',
        'menu_id',
        'creation_date',
        'modification_date',
    ];

    public function orders(): array
    {
        return $this->belongsToMany(Order::class, 'order_dishes', 'dish_id', 'order_id');
    }

    /**
     * @throws Exception
     */
    public function menu(): ?Menu
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function jsonSerialize(): array
    {
        return [
            'id'                => $this->id,
            'creation_date'     => $this->creation_date,
            'modification_date' => $this->modification_date,
            'name'              => $this->name,
            'total'             => $this->total,
            'quantity'          => ($this->pivot->quantity ?? null)
        ];
    }
}