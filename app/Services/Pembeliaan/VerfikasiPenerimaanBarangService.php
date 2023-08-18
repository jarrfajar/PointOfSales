<?php

namespace App\Services\Pembeliaan;
use App\Models\DetailPenerimaanBarang;
use App\Models\HeaderPenerimaanBarang;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class VerfikasiPenerimaanBarangService
{
    public static function index()
    {
        $penerimaan_barang = HeaderPenerimaanBarang::with('supplier','gudang')
                                    ->where('kode_cabang', auth()->user()->kode_cabang)
                                    ->where('status', 0)
                                    ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($penerimaan_barang)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.verifikasiPenerimaanBarang', ['type_menu' => 'pembelian']);
    }

    public static function verifikasi(object $request, string $nomor_bapb)
    {
        DB::beginTransaction();
        try {
            $penerimaan_barang = HeaderPenerimaanBarang::where('nomor_bapb', $nomor_bapb)->first();
            
            $valid_barang_ids = [];
            $retur_barang_ids = [];
            
            foreach ($request->barangs as $value) {
                if ($value['status'] == 1) {
                    array_push($valid_barang_ids, $value['barang_id']);
                    StockService::init($value['barang_id'], $value['jumlah'])->increase();
                } else {
                    array_push($retur_barang_ids, $value['barang_id']);
                }
            }

            // jika ada $retur_barang_ids maka di bapb tersebut ada barang yang di retur 
            $penerimaan_barang->update(['status' => isset($retur_barang_ids) ? 2 : 1]);

            // valid = 1, retur = 2
            self::validOrReturItem($penerimaan_barang->id, $valid_barang_ids, 1);
            self::validOrReturItem($penerimaan_barang->id, $retur_barang_ids, 2);
    
            DB::commit();
        
            return response()->json(['data' => 'data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function validOrReturItem(string $penerimaan_barang_id, array $barang_ids, int $status)
    {
        return DetailPenerimaanBarang::where('penerimaan_barang_id', $penerimaan_barang_id)->whereIn('barang_id', $barang_ids)->update(['status' => $status]);
    }
}
