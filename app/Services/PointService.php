<?php

namespace App\Services;
use App\Models\Members;

class PointService
{

    public static function priceToPoint(float $price)
    {
        return floor($price / 200);
    }
    
    public static function increasePoint(float $price, int $memberId)
    {
        $member = Members::find($memberId);
        $member->point += self::priceToPoint($price);
        $member->update();
    }

    public static function decreasePoint(int $point, int $memberId)
    {
        $member = Members::find($memberId);
        $member->point -= $point;
        $member->update();
    }
}
