<?php

namespace App\Services;
use App\Models\Gudang;
use Yajra\DataTables\DataTables;

class GudangService
{
    public static function index()
    {
        $gudang = Gudang::orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($gudang)->addIndexColumn()->make(true);
        }

        return view('pages.master.gudang',['type_menu' => 'layout']);
    }

    public static function store(object $request)
    {
        $gudang = Gudang::create([
            'nama'   => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json(['data' => $gudang]);
    }

    public static function show(int $id)
    {
        $gudang = Gudang::find($id);

        return response()->json(['data' => $gudang]);
    }

    public static function update(object $request, int $id)
    {
        $gudang = Gudang::find($id);
        $gudang->update([
            'nama'   => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json(['data' => $gudang]);
    }

    public static function delete(int $id)
    {
        $gudang = Gudang::find($id)->delete();

        return response()->json(['data' => $gudang]);
    }

    public static function search(object $request)
    {
        $gudang = Gudang::when($request->search, function($query) use ($request) {
                        return $query->where('nama', 'LIKE', "%$request->search%");
                    })
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        
        return response()->json(['data' => $gudang]);
    }
}
