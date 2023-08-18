<?php

namespace App\Services\Kasir;

class KasirService
{
    public static function index()
    {
        return view('pages.kasir',['type_menu' => 'kasir']);
    }
}
