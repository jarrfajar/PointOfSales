<?php

namespace App\Services;
use App\Models\Members;

class PointService
{

    public static function priceToPoint(float $price)
    {
        return floor($price / 200);
    }
    
    public static function increasePoint(float $price, string $phone)
    {
        $member = Members::where('nomor_telpon', $phone)->first();
        $member->poin += self::priceToPoint($price);
        $member->update();
    }

    public static function decreasePoint(int $point, string $phone)
    {
        $member = Members::where('nomor_telpon', $phone)->first(); 
        $member->poin -= $point;
        $member->update();
    }
}
