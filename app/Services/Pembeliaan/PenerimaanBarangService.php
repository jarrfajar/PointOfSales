<?php

namespace App\Services\Pembeliaan;
use App\Models\DetailPenerimaanBarang;
use App\Models\HeaderPenerimaanBarang;
use App\Models\HeaderPurchaseOrder;
use App\Services\SerialNumberService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PenerimaanBarangService
{
    public static function index()
    {
        $penerimaan_barang = HeaderPenerimaanBarang::with('supplier','gudang','barangs')
                                               ->where('kode_cabang', auth()->user()->kode_cabang)
                                               ->orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($penerimaan_barang)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.penerimaanBarang',['type_menu' => 'layout']);
    }

    public static function store(object $request)
    {
        DB::beginTransaction();
        try {
            $header_penerimaan_barang = HeaderPenerimaanBarang::create([
                'kode_cabang'       => auth()->user()->kode_cabang,
                'nomor_bapb'        => SerialNumberService::genereteNumber('FJR', 'BAPB'),
                'nomor_faktur'      => SerialNumberService::genereteNumber('FJR', 'FKTR'),
                'purchase_order_id' => $request->purchase_order_id,
                'nomor_resi'        => $request->nomor_resi,
                'gudang_id'         => $request->gudang_id,
                'supplier_id'       => $request->supplier_id,
                'tanggal_po'        => $request->tanggal_po,
                'tanggal_terima'    => $request->tanggal_terima,
                'tanggal_tempo'     => $request->tanggal_tempo,
                'deskripsi'         => $request->deskripsi,
                'total_harga'       => $request->total_harga,
                'sub_total'         => $request->sub_total,
                'diskon'            => $request->diskon,
                'ppn'               => $request->ppn,
            ]);
    
            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'penerimaan_barang_id' => $header_penerimaan_barang->id,
                    'barang_id'            => $value['barang_id'],
                    'jumlah'               => $value['jumlah'],
                    'satuan_id'            => $value['satuan_id'],
                    'harga'                => $value['harga'],
                    'diskon_persen'        => $value['diskon_persen'],
                    'diskon_rp'            => $value['diskon_rp'],
                    'ppn'                  => $value['ppn'],
                    'total_harga'          => $value['total_harga'],
                ];
            }
            
            DetailPenerimaanBarang::insert($data);

            DB::commit();
        
            return response()->json(['data' => 'data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
