<?php

namespace App\Model;

use Exception;

class SmsResponse extends Model
{
    /** @var string Nom de la table en base */
    protected static string $table = 'sms_responses';

    /** @var string[] Tableau des propriétés pouvant être directement mises à jour dans l'interface */
    protected static array $fillables = [
        'message',
        'destination',
        'sms_batch_id',
        'status',
        'menu_id',
        'creation_date',
    ];

    /**
     * Relation avec la table Menu
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