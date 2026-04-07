<?php

namespace App\Model;

use Exception;

class Menu extends Model
{
    /** @var string Nom de la table en base */
    protected static string $table = 'menus';

    /** @var string[] Tableau des propriétés pouvant être directement mises à jour dans l'interface */
    protected static array $fillables = [
        'id',
        'img_src',
        'img_figcaption',
        'is_open',
        'creation_date',
        'modification_date',
    ];

    /** @var ?Dish[]  */
    private ?array $dishesCache = null;

    /**
     * Relation avec la table dish
     *
     * @return Dish[]
     *
     * @throws Exception
     */
    public function dishes(): array
    {
        if (is_null($this->dishesCache)) {
            $this->dishesCache = $this->hasMany(Dish::class, 'menu_id');
        }
        return $this->dishesCache;
    }

    /**
     * Relation avec la table smsResponse
     *
     * @return SmsResponse
     *
     * @throws Exception
     */
    public function smsResponse()
    {
        return $this->hasOne(SmsResponse::class, 'menu_id');
    }
}
