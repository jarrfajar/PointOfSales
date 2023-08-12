<?php

namespace App\Services;
use App\Models\Barang;
use App\Models\StockBarang;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BarangService
{
    public static function index()
    {
        $barangs = Barang::with('kategori','satuan','gudang')->where('kode_cabang', auth()->user()->kode_cabang)->orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($barangs)->addIndexColumn()->make(true);
        }

        return view('pages.master.barang',['type_menu' => 'master']);
    }

    public static function store(object $request)
    {
        DB::beginTransaction();
        try {
            $barang = Barang::create([
                'kode_cabang'        => auth()->user()->kode_cabang,
                'gudang_id'          => $request->gudang_id,
                'kode_barang'        => $request->kode_barang,
                'nama_barang'        => $request->nama_barang,
                'kategori_id'        => $request->kategori_id,
                'harga_beli'         => $request->harga_beli,
                'harga_jual'         => $request->harga_jual,
                'satuan_id'          => $request->satuan_id,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            ]);

            StockBarang::create([
                'kode_cabang' => auth()->user()->kode_cabang,
                'gudang_id'   => $request->gudang_id,
                'barang_id'   => $barang->id,
                'masuk'       => 0,
                'keluar'      => 0,
                'jumlah'      => 0,
            ]);
    
            DB::commit();
            return response()->json(['data' => $barang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function show(int $id)
    {
        $barang = Barang::with('kategori','satuan','gudang')->find($id);

        return response()->json(['data' => $barang]);
    }

    public static function update(object $request, int $id)
    {
        $barang = Barang::find($id);
        $barang->update([
            'kode_cabang'        => auth()->user()->kode_cabang,
            'gudang_id'          => $request->gudang_id,
            'kode_barang'        => $request->kode_barang,
            'nama_barang'        => $request->nama_barang,
            'kategori_id'        => $request->kategori_id,
            'harga_beli'         => $request->harga_beli,
            'harga_jual'         => $request->harga_jual,
            'satuan_id'          => $request->satuan_id,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
        ]);

        return response()->json(['data' => $barang]);
    }

    public static function delete(int $id)
    {
        $barang = Barang::find($id)->delete();

        return response()->json(['data' => $barang]);
    }

    public static function search(object $request)
    {
        $barang = Barang::when($request->search, function($query) use ($request) {
                        return $query->where('kode_barang', 'LIKE', "%$request->search%")
                                     ->orWhere('nama_barang', 'LIKE', "%$request->search%");
                    })
                    ->where('kode_cabang', auth()->user()->kode_cabang)
                    ->where('gudang_id', $request->gudang)
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        
        return response()->json(['data' => $barang]);
    }
}
