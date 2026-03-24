<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace App\Service\Provider;
class Ranker{
    public static function sort(array $items): array{
        usort($items, function($a,$b){
            $sa=(float)($a['confidence'] ?? 0.0);
            $sb=(float)($b['confidence'] ?? 0.0);
            if($sa==$sb) return 0;
            return ($sa>$sb)?-1:1;
        });
        return $items;
    }
}
