<?php

namespace App\Services;
use App\Models\Branch;
use Yajra\DataTables\DataTables;

class BranchService
{
    public static function index()
    {
        $branchs = Branch::orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($branchs)->addIndexColumn()->make(true);
        }

        return view('pages.master.cabang',['type_menu' => 'master']);
    }

    public static function store(object $request)
    {
        $branch = Branch::create([
            'nama'          => $request->nama,
            'alamat'        => $request->alamat,
            'kepala_cabang' => $request->kepala_cabang
        ]);

        return response()->json(['data' => $branch]);
    }

    public static function show(int $id)
    {
        $branch = Branch::find($id);

        return response()->json(['data' => $branch]);
    }

    public static function update(object $request, int $id)
    {
        $branch = Branch::find($id);
        $branch->update([
            'nama'          => $request->nama,
            'alamat'        => $request->alamat,
            'kepala_cabang' => $request->kepala_cabang
        ]);

        return response()->json(['data' => $branch]);
    }

    public static function delete(int $id)
    {
        $branch = Branch::find($id)->delete();

        return response()->json(['data' => $branch]);
    }
}
