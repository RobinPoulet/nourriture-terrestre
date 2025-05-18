<?php

namespace App\Helper;
abstract class HelperOrder
{
    public static function handleTodayOrders(array $resultsOrder): array
    {
        $returnValue = [];

        foreach ($resultsOrder as $order) {
            if (isset($returnValue[$order->id])) {
                $returnValue[$order['order_id']]['dishes'][$order['dish_id']] = $order['quantity'];
            } else {
                $returnValue[$order['order_id']] = [
                    'user_id' => $order['user_id'],
                    'perso' => $order['perso'],
                    'dishes' => [
                        $order['dish_id'] => $order['quantity']
                    ]
                ];
            }
        }

        return $returnValue;
    }

    public static function checkDishesInput(array $dishes): string
    {
        $returnValue = "";

        // Vérifier si tous les éléments sont des entiers positifs
        $filteredDishes = array_filter(
            $dishes,
            fn($quantity) => (is_numeric($quantity) && ctype_digit((string)$quantity) && (int)$quantity > 0)
        );

        if (empty($filteredDishes)) {
            $returnValue = "Il faut commander au moins un article";
        }

        return $returnValue;
    }
}