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
        $penerimaan_barang = HeaderPenerimaanBarang::with('supplier','gudang')
                                               ->where('kode_cabang', auth()->user()->kode_cabang)
                                               ->orderBy('id', 'desc');

        if (request()->wantsJson()) {
            return DataTables::of($penerimaan_barang)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.penerimaanBarang',['type_menu' => 'pembelian']);
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
                'status'            => 0,
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
                    'status'               => 0,
                ];
            }
            
            DetailPenerimaanBarang::insert($data);

            HeaderPurchaseOrder::find($request->purchase_order_id)->update(['bapb' => 1]);

            DB::commit();
        
            return response()->json(['data' => $header_penerimaan_barang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function show(int $id)
    {
        $penerimaan_barang = HeaderPenerimaanBarang::with('purchaseOrder','supplier','gudang','barangs.barang','barangs.satuan')->find($id);

        return response()->json(['data' => $penerimaan_barang]);
    }

    public static function update(object $request, int $id)
    {
        DB::beginTransaction();
        try {
            $header_penerimaan_barang = HeaderPenerimaanBarang::with('barangs')->find($id);

            $header_penerimaan_barang->update([
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

            $header_penerimaan_barang->barangs()->delete();
    
            $data = [];
            foreach ($request->barang as $key => $value) {
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
                    'status'               => 0,
                ];
            }
            
            DetailPenerimaanBarang::insert($data);

            HeaderPurchaseOrder::find($request->purchase_order_id)->update(['bapb' => 1]);

            DB::commit();
        
            return response()->json(['data' => $header_penerimaan_barang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $penerimaan_barang = HeaderPenerimaanBarang::with('barangs')->find($id);

            $penerimaan_barang->barangs->map(function($item, $key){
                $stock = StockBarang::where('barang_id', $item['barang_id'])->first();
                $stock->update([
                    'masuk'  => $stock->masuk - $item['jumlah'],
                    'jumlah' => $stock->jumlah - $item['jumlah']
                ]);
            });

            $penerimaan_barang->barangs()->delete();
            $penerimaan_barang->delete();
    
            DB::commit();
        
            return response()->json(['data' => $penerimaan_barang]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function search(object $request, int $supplier_id)
    {
        $penerimaan_barang = HeaderPenerimaanBarang::when($request->search, function($query) use ($request) {
                                return $query->where('nomor_bapb', 'LIKE', "%$request->search%");
                            })
                            ->where('supplier_id', $supplier_id)
                            ->orderBy('id', 'desc')
                            ->limit(10)
                            ->get();
                          
        
        return response()->json(['data' => $penerimaan_barang]);
    }
}
