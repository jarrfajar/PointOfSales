<?php

namespace App\Http\Controllers;

use App\Services\StokBarang\StokBarangService;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public function index()
    {
        return StokBarangService::index();
    }

    public function masuk()
    {
        return StokBarangService::masuk();
    }
}
