<?php

namespace App\Http\Controllers;

use App\Services\Pembeliaan\ReturPenerimaanBarangService;
use Illuminate\Http\Request;

class ReturPenerimaanBarangController extends Controller
{
    public function index()
    {
        return ReturPenerimaanBarangService::index();
    }
}
