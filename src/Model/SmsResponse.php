<?php

namespace App\Model;

use Exception;

class SmsResponse extends Model
{
    protected static string $table = 'sms_responses';
    protected static array $fillables = [
        'bulk_id',
        'message_id',
        'destination',
        'status_group_id',
        'status_group_name',
        'status_id',
        'status_name',
        'status_description',
        'status_action',
        'creation_date',
    ];

    /**
     * @throws Exception
     */
//    public function dishes(): array
//    {
//        return $this->hasMany(Dish::class, 'sms_response_id');
//    }
}