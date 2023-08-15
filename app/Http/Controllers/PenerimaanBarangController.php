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

    public function show(int $id)
    {
        return PenerimaanBarangService::show($id);
    }

    public function update(PenerimaanBarangRequest $request, int $id)
    {
        return PenerimaanBarangService::update($request, $id);
    }

    public function destroy(int $id)
    {
        return PenerimaanBarangService::delete($id);
    }

    public function search(Request $request, int $supplier_id)
    {
        return PenerimaanBarangService::search($request, $supplier_id);
    }
}
