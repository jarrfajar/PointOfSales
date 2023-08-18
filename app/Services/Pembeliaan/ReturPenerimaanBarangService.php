<?php

namespace App\Services\Pembeliaan;
use App\Models\DetailPenerimaanBarang;
use App\Models\HeaderReturPenerimaanBarang;
use Yajra\DataTables\DataTables;

class ReturPenerimaanBarangService
{
    public static function index()
    {
        $retur_penerimaan_barang = HeaderReturPenerimaanBarang::with('barangs','supplier','gudang')->orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($retur_penerimaan_barang)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.returPenerimaanBarang', ['type_menu' => 'pembelian']);
    }
}
