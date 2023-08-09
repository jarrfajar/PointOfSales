<?php

namespace App\Http\Controllers;

use App\Services\Pembeliaan\PenerimaanBarangService;
use Illuminate\Http\Request;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        return PenerimaanBarangService::index();
    }
}
