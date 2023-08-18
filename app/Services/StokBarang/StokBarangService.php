<?php

namespace App\Services\StokBarang;
use App\Models\DetailPenerimaanBarang;
use App\Models\StockBarang;
use Yajra\DataTables\DataTables;

class StokBarangService
{
    public static function index()
    {
        $stok_barang = StockBarang::with('gudang','barang.kategori','barang.satuan')
                                    ->where('kode_cabang', auth()->user()->kode_cabang)
                                    ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($stok_barang)->addIndexColumn()->make(true);
        }

        return view('pages.stokBarang.stokBarang', ['type_menu' => 'stok-barang']);
    }

    public static function masuk()
    {
        $penerimaan_barang = DetailPenerimaanBarang::with('penerimaanBarang.supplier','penerimaanBarang.gudang','penerimaanBarang.purchaseOrder','barang.kategori','barang.satuan')
                                                    ->whereRelation('penerimaanBarang','kode_cabang', auth()->user()->kode_cabang)
                                                    ->where('status', 1)
                                                    ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
        return DataTables::of($penerimaan_barang)->addIndexColumn()->make(true);
        }

        return view('pages.stokBarang.barangMasuk', ['type_menu' => 'stok-barang']);
    }
}
