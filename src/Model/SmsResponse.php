<?php

namespace App\Model;

use Exception;

class SmsResponse extends Model
{
    protected static string $table = 'sms_responses';
    protected static array $fillables = [
        'message',
        'destination',
        'sms_batch_id',
        'status',
        'menu_id',
        'creation_date',
    ];

    /**
     * @throws Exception
     */
    public function menu(): ?Menu
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}