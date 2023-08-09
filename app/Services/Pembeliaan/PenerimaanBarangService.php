<?php

namespace App\Services\Pembeliaan;
use App\Models\HeaderPurchaseOrder;
use Yajra\DataTables\DataTables;

class PenerimaanBarangService
{
    public static function index()
    {
        $penerimaanBarang = HeaderPurchaseOrder::with('supplier','gudang','purchaseOrders')
                                               ->where('kode_cabang', auth()->user()->kode_cabang)
                                               ->where('status', 1)
                                               ->orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($penerimaanBarang)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.penerimaanBarang',['type_menu' => 'layout']);
    }
}
