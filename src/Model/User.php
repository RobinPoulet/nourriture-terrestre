<?php

namespace App\Model;

use App\Model\Model;
use Exception;

class User extends Model
{
    /** @var string Nom de la table en base */
    protected static string $table = 'users';

    /** @var string[] Tableau des propriétés pouvant être directement mises à jour dans l'interface */
    protected static array $fillables = [
        'name',
    ];

    /**
     * Relation avec la table Order
     *
     * @return Order[]
     *
     * @throws Exception
     */
    public function orders(): array
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}