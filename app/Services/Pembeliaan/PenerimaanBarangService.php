<?php

namespace App\Services\Pembeliaan;
use App\Models\DetailPenerimaanBarang;
use App\Models\HeaderPenerimaanBarang;
use App\Models\HeaderPurchaseOrder;
use App\Models\StockBarang;
use App\Services\SerialNumberService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PenerimaanBarangService
{
    public static function index()
    {
        $penerimaanBarang = HeaderPenerimaanBarang::with('supplier','gudang')
                                               ->where('kode_cabang', auth()->user()->kode_cabang)
                                               ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($penerimaanBarang)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.penerimaanBarang',['type_menu' => 'pembelian']);
    }

    public static function store(object $request)
    {
        DB::beginTransaction();
        try {
            $headerPenerimaanBarang = HeaderPenerimaanBarang::create([
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
                'status'            => 0,
            ]);
    
            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'penerimaanBarang_id' => $headerPenerimaanBarang->id,
                    'barang_id'            => $value['barang_id'],
                    'jumlah'               => $value['jumlah'],
                    'satuan_id'            => $value['satuan_id'],
                    'harga'                => $value['harga'],
                    'diskon_persen'        => $value['diskon_persen'],
                    'diskon_rp'            => $value['diskon_rp'],
                    'ppn'                  => $value['ppn'],
                    'total_harga'          => $value['total_harga'],
                    'status'               => 0,
                ];
            }
            
            DetailPenerimaanBarang::insert($data);

            HeaderPurchaseOrder::find($request->purchase_order_id)->update(['bapb' => 1]);

            DB::commit();
        
            return response()->json(['data' => $headerPenerimaanBarang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function show(int $id)
    {
        $penerimaanBarang = HeaderPenerimaanBarang::with('purchaseOrder','supplier','gudang','barangs.barang','barangs.satuan')->find($id);

        return response()->json(['data' => $penerimaanBarang]);
    }

    public static function update(object $request, int $id)
    {
        DB::beginTransaction();
        try {
            $header_penerimaanBarang = HeaderPenerimaanBarang::with('barangs')->find($id);

            $header_penerimaanBarang->update([
                'kode_cabang'       => auth()->user()->kode_cabang,
                'nomor_bapb'        => $request->nomor_bapb,
                'nomor_faktur'      => $request->nomor_faktur,
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
                'status'            => 0,
            ]);

            $header_penerimaanBarang->barangs()->delete();
    
            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'penerimaanBarang_id' => $header_penerimaanBarang->id,
                    'barang_id'            => $value['barang_id'],
                    'jumlah'               => $value['jumlah'],
                    'satuan_id'            => $value['satuan_id'],
                    'harga'                => $value['harga'],
                    'diskon_persen'        => $value['diskon_persen'],
                    'diskon_rp'            => $value['diskon_rp'],
                    'ppn'                  => $value['ppn'],
                    'total_harga'          => $value['total_harga'],
                    'status'               => 0,
                ];
            }
            
            DetailPenerimaanBarang::insert($data);

            HeaderPurchaseOrder::find($request->purchase_order_id)->update(['bapb' => 1]);

            DB::commit();
        
            return response()->json(['data' => $header_penerimaanBarang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $penerimaanBarang = HeaderPenerimaanBarang::with('barangs')->find($id);

            $penerimaanBarang->barangs->map(function($item){
                $stock = StockBarang::where('barang_id', $item['barang_id'])->first();
                $stock->update([
                    'masuk'  => $stock->masuk - $item['jumlah'],
                    'jumlah' => $stock->jumlah - $item['jumlah']
                ]);
            });

            $penerimaanBarang->barangs()->delete();
            $penerimaanBarang->delete();
    
            DB::commit();
        
            return response()->json(['data' => $penerimaanBarang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    /**
     * Get penerimaan barang for retur
     */
    public static function search(object $request, int $supplier_id)
    {
        $penerimaanBarang = HeaderPenerimaanBarang::select('id', 'nomor_bapb')
                            ->where('supplier_id', $supplier_id)
                            ->where('status', 2)
                            ->orderBy('id', 'desc')
                            ->limit(10)
                            ->get();
                          
        
        return response()->json(['data' => $penerimaanBarang]);
    }

    public static function showbarangRetur(int $id)
    {
        $penerimaanBarang = HeaderPenerimaanBarang::with(['barangs' => fn($query) => $query->where('status', 2), 'supplier','gudang','barangs.barang','barangs.satuan'])->find($id);

        return response()->json(['data' => $penerimaanBarang]);
    }
}
