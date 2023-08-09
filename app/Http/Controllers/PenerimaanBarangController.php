<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenerimaanBarangRequest;
use App\Services\Pembeliaan\PenerimaanBarangService;
use Illuminate\Http\Request;

class PenerimaanBarangController extends Controller
{
    public function index()
    {
        return PenerimaanBarangService::index();
    }

    public function store(PenerimaanBarangRequest $request)
    {
        return PenerimaanBarangService::store($request);
    }
}
