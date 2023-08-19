<?php

namespace App\Services\Kasir;
use App\Models\DetailPenjualan;
use App\Models\HeaderPenjualan;
use App\Services\PointService;
use App\Services\SerialNumberService;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class KasirService
{
    
    public static function index()
    {
        return view('pages.kasir',['type_menu' => 'kasir']);
    }

    public static function store(object $request)
    {
        DB::beginTransaction();
        try {
            $headerPenjualan = HeaderPenjualan::create([
                'kode_cabang'  => auth()->user()->kode_cabang,
                'invoice'      => SerialNumberService::createInvoice(),
                'tanggal'      => date('Y-m-d H:i:s'),
                'kasir_id'     => auth()->user()->id,
                'member_id'    => $request->member_id,
                'point'        => isset($request->jumlah_point) > 0 ? 1 : 0,
                'jumlah_point' => $request->jumlah_point,
                'total_harga'  => $request->total_harga,
            ]);

            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'hpenjualan_id' => $headerPenjualan->id,
                    'barang_id'     => $value['barang_id'],
                    'qty'           => $value['qty'],
                    'satuan_id'     => $value['satuan_id'],
                    'harga'         => $value['harga'],
                    'diskon'        => $value['diskon'] ?? 0,
                    'total'         => $value['total'],
                ];

                // decrease stock unit
                StockService::init($value['barang_id'], $value['qty'])->decrease();
            }
            
            DetailPenjualan::insert($data);

            if ($request->usePoint != 0) {
                // decrease point
                PointService::decreasePoint($request->jumlah_point, $request->member_id);
            }

            if ($request->member_id != 0) {
                PointService::increasePoint(floatval($request->total_harga), $request->member_id);
            }
    
            DB::commit();
        
            return response()->json(['data' => $headerPenjualan]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
