<?php

namespace App\Model;

use Exception;

class Menu extends Model
{
    protected static string $table = 'menus';

    protected string $img_src;
    protected string $img_figcaption;
    protected bool $is_open;
    protected static array $fillables = [
        'id',
        'img_src',
        'img_figcaption',
        'is_open',
        'creation_date',
        'modification_date',
    ];


    private ?array $dishesCache = null;

    /**
     * @throws Exception
     */
    public function dishes(): array
    {
        if (is_null($this->dishesCache)) {
            $this->dishesCache = $this->hasMany(Dish::class, 'menu_id');
        }
        return $this->dishesCache;
    }
}
