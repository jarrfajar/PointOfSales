<?php

namespace App\Services;
use App\Models\Members;
use Yajra\DataTables\DataTables;

class MembersService
{
    public static function index()
    {
        $members = Members::orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($members)->addIndexColumn()->make(true);
        }

        return view('pages.member',['type_menu' => 'pembelian']);
    }

    public static function store(object $request)
    {
        $member = Members::create([
                'nama'           => $request->nama,
                'nomor_telpon'   => $request->nomor_telpon,
                'tanggal_daftar' => $request->tanggal_daftar,
                'point'          => 0,
            ]);
        
        return response()->json(['data' => $member]);
    }

    public static function show(int $id)
    {
        $member = Members::find($id);
        return response()->json(['data' => $member]);
    }

    public static function update(object $request, int $id)
    {
        $member = Members::find($id)->update([
                'nama'           => $request->nama,
                'nomor_telpon'   => $request->nomor_telpon,
                'tanggal_daftar' => $request->tanggal_daftar,
            ]);
        
        return response()->json(['data' => $member]);
    }

    public static function delete(int $id)
    {
        $member = Members::find($id)->delete();
        return response()->json(['data' => $member]);
    }

    public static function getMember(string $phone)
    {
        $member = Members::where('nomor_telpon', $phone)->first();
        return response()->json(['data' => $member]);
    }

    public static function search(object $request)
    {
        $member = Members::when($request->search, function($query) use ($request) {
                        return $query->where('nomor_telpon', 'LIKE', "%$request->search%")
                                     ->orWhere('nama', 'LIKE', "%$request->search%");
                    })
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        
        return response()->json(['data' => $member]);
    }
}
