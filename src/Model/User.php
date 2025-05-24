<?php

namespace App\Model;

use App\Model\Model;
use Exception;

class User extends Model
{
    protected static string $table = 'users';

    protected string $name;

    protected static array $fillables = [
        'name',
    ];

    /**
     * @throws Exception
     */
    public function orders(): array
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}