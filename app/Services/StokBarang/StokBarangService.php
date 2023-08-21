<?php

namespace App\Services\StokBarang;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPenjualan;
use App\Models\StockBarang;
use Yajra\DataTables\DataTables;

class StokBarangService
{
    public static function index()
    {
        $stokBarang = StockBarang::with('gudang','barang.kategori','barang.satuan')
                                    ->where('kode_cabang', auth()->user()->kode_cabang)
                                    ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($stokBarang)->addIndexColumn()->make(true);
        }

        return view('pages.stokBarang.stokBarang', ['type_menu' => 'stok-barang']);
    }


    public static function stockSearch(object $request)
    {
        $stokBarang = StockBarang::with('barang')->when($request->search, function($query) use ($request) {
                        return $query->whereRelation('barang', 'kode_barang', 'LIKE', "%{$request->search}%")
                                     ->orWhereRelation('barang', 'nama_barang', 'LIKE', "%{$request->search}%");
                    })
                    ->where([
                        ['kode_cabang', auth()->user()->kode_cabang],
                        ['gudang_id', $request->gudang],
                        ['jumlah', '>', 0],
                    ])
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        return response()->json(['data' => $stokBarang]);
    }

    public static function masuk()
    {
        $penerimaanBarang = DetailPenerimaanBarang::with('penerimaanBarang.supplier','penerimaanBarang.gudang','penerimaanBarang.purchaseOrder','barang.kategori','barang.satuan')
                                                    ->whereRelation('penerimaanBarang','kode_cabang', auth()->user()->kode_cabang)
                                                    ->where('status', 1)
                                                    ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
        return DataTables::of($penerimaanBarang)->addIndexColumn()->make(true);
        }

        return view('pages.stokBarang.barangMasuk', ['type_menu' => 'stok-barang']);
}

    public static function keluar(object $request)
    {
        $keluar = DetailPenjualan::with('sale.gudang','barang.kategori','barang.satuan')
                                ->whereRelation('sale','kode_cabang', auth()->user()->kode_cabang)
                                ->when(isset($request->start), function($query) use ($request) {
                                    return $query->whereRelation('sale', 'tanggal', '>=', $request->start.' 00:00:00')
                                                 ->whereRelation('sale', 'tanggal', '<=', $request->end.' 23:59:59');
                                })
                                ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
        return DataTables::of($keluar)->addIndexColumn()->make(true);
        }

        return view('pages.stokBarang.barangKeluar', ['type_menu' => 'stok-barang']);
    }
}
