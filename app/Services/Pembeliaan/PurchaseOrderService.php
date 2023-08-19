<?php

namespace App\Services\Pembeliaan;
use App\Models\DetailPurchaseOrder;
use App\Models\HeaderPurchaseOrder;
use App\Services\SerialNumberService;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PurchaseOrderService
{
    public static function index()
    {
        $purchaseOrder = HeaderPurchaseOrder::with('supplier','gudang','purchaseOrders')->where('kode_cabang', auth()->user()->kode_cabang)->orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($purchaseOrder)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.purchaseOrder',['type_menu' => 'pembelian']);
    }

    public static function store(object $request)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = HeaderPurchaseOrder::create([
                'kode_cabang'          => auth()->user()->kode_cabang,
                'nomor_purchase_order' => SerialNumberService::genereteNumber(auth()->user()->kode_cabang, 'PO'),
                'gudang_id'            => $request->gudang_id,
                'tanggal'              => $request->tanggal,
                'supplier_id'          => $request->supplier_id,
                'total_harga'          => $request->total_harga,
                'deskripsi'            => $request->deskripsi,
                'status'               => 0,
                'bapb'                 => 0,
            ]);

            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'hpurchase_order_id' => $purchaseOrder->id,
                    'barang_id'          => $value['barang_id'],
                    'jumlah'             => $value['jumlah'],
                    'satuan_id'          => $value['satuan_id'],
                    'harga'              => $value['harga'],
                    'total_harga'        => $value['total_harga'],
                ];
            }
            
            DetailPurchaseOrder::insert($data);
    
            DB::commit();
        
            return response()->json(['data' => 'data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function update(object $request, int $id)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = HeaderPurchaseOrder::find($id);
            $purchaseOrder->purchaseOrders()->delete();

            $purchaseOrder->update([
                'gudang_id'   => $request->gudang_id,
                'tanggal'     => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'total_harga' => $request->total_harga,
                'deskripsi'   => $request->deskripsi,
                'status'      => 0,
                'bapb'        => 0,
            ]);

            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'hpurchase_order_id' => $purchaseOrder->id,
                    'barang_id'          => $value['barang_id'],
                    'jumlah'             => $value['jumlah'],
                    'satuan_id'          => $value['satuan_id'],
                    'harga'              => $value['harga'],
                    'total_harga'        => $value['total_harga'],
                ];
            }
            
            DetailPurchaseOrder::insert($data);
    
            DB::commit();
        
            return response()->json(['data' => 'data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $purchaseOrder = HeaderPurchaseOrder::find($id);
            $purchaseOrder->purchaseOrders()->delete();

            $purchaseOrder->delete();
    
            DB::commit();
        
            return response()->json(['data' => 'data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function show(int $id)
    {
        $purchaseOrder = HeaderPurchaseOrder::with('supplier','gudang','purchaseOrders.satuan','purchaseOrders.barang')->where('kode_cabang', auth()->user()->kode_cabang)->find($id);

        return response()->json(['data' => $purchaseOrder]);
    }

    public static function get(int $id)
    {
        $purchaseOrder = HeaderPurchaseOrder::select('id','nomor_purchase_order','tanggal')
                                            ->where([
                                                ['supplier_id', $id],
                                                ['kode_cabang', auth()->user()->kode_cabang],
                                                ['status', 1],
                                                ['bapb', 0]
                                            ])
                                            ->get();

        return response()->json(['data' => $purchaseOrder]);
    }

    public static function approve(int $id)
    {
        $purchaseOrder = HeaderPurchaseOrder::find($id)->update(['status' => 1]);
        
        return response()->json(['data' => $purchaseOrder]);
    }

    public static function reject(int $id)
    {
        $purchaseOrder = HeaderPurchaseOrder::find($id)->update(['status' => 2]);

        return response()->json(['data' => $purchaseOrder]);
    }
}
