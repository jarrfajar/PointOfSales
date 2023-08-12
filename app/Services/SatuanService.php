<?php

namespace App\Services;
use App\Models\Satuan;
use Yajra\DataTables\DataTables;

class SatuanService
{
    public static function index()
    {
        $satuan = Satuan::orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($satuan)->addIndexColumn()->make(true);
        }

        return view('pages.master.satuan',['type_menu' => 'master']);
    }

    public static function store(object $request)
    {
        $satuan = Satuan::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json(['data' => $satuan]);
    }

    public static function show(int $id)
    {
        $satuan = Satuan::find($id);

        return response()->json(['data' => $satuan]);
    }

    public static function update(object $request, int $id)
    {
        $satuan = Satuan::find($id);
        $satuan->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json(['data' => $satuan]);
    }

    public static function delete(int $id)
    {
        $satuan = Satuan::find($id)->delete();

        return response()->json(['data' => $satuan]);
    }

    public static function search(object $request)
    {
        $satuan = Satuan::when($request->search, function($query) use ($request) {
                        return $query->where('nama', 'LIKE', "%$request->search%");
                    })
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        
        return response()->json(['data' => $satuan]);
    }
}
