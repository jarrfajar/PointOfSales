<?php

namespace App\Services;
use App\Models\Kategori;
use Yajra\DataTables\DataTables;

class KategoriService
{
    public static function index(){
        $kategori = Kategori::orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($kategori)->addIndexColumn()->make(true);
        }

        return view('pages.master.kategori',['type_menu' => 'layout']);
    }

    public static function store(object $request)
    {
        $barang = Kategori::create([
            'nama' => $request->nama
        ]);

        return response()->json(['data' => $barang]);
    }

    public static function show(int $id)
    {
        $barang = Kategori::find($id);

        return response()->json(['data' => $barang]);
    }

    public static function update(object $request, int $id)
    {
        $barang = Kategori::find($id);
        $barang->update([
            'nama' => $request->nama
        ]);

        return response()->json(['data' => $barang]);
    }

    public static function delete(int $id)
    {
        $barang = Kategori::find($id)->delete();

        return response()->json(['data' => $barang]);
    }

    public static function search(object $request)
    {
        $kategori = Kategori::when($request->search, function($query) use ($request) {
                        return $query->where('nama', 'LIKE', "%$request->search%");
                    })
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        
        return response()->json(['data' => $kategori]);
    }
}
