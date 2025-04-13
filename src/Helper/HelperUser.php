<?php

namespace App\Helper;

abstract class HelperUser
{
    public static function tabUsersById(array $users): array
    {
        $returnValue = [];

        foreach ($users as $user) {
            $returnValue[$user['ID']] = $user['NAME'];
        }

        return $returnValue;
    }
}