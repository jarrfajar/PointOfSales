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
        $purchase_order = HeaderPurchaseOrder::with('supplier','gudang','purchaseOrders')->where('kode_cabang', auth()->user()->kode_cabang)->orderBy('id', 'desc');

        if (request()->ajax()) {
            return DataTables::of($purchase_order)->addIndexColumn()->make(true);
        }

        return view('pages.pembelian.purchaseOrder',['type_menu' => 'layout']);
    }

    public static function store(object $request)
    {
        DB::beginTransaction();
        try {
            $purchase_order = HeaderPurchaseOrder::create([
                'kode_cabang'          => auth()->user()->kode_cabang,
                'nomor_purchase_order' => SerialNumberService::genereteNumber(auth()->user()->kode_cabang, 'PO'),
                'gudang_id'            => $request->gudang_id,
                'tanggal'              => $request->tanggal,
                'supplier_id'          => $request->supplier_id,
                'total_harga'          => $request->total_harga,
                'deskripsi'            => $request->deskripsi,
                'status'               => 0,
            ]);

            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'hpurchase_order_id' => $purchase_order->id,
                    'kode_barang'        => $value['kode_barang'],
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
            $purchase_order = HeaderPurchaseOrder::find($id);
            $purchase_order->purchaseOrders()->delete();

            $purchase_order->update([
                'gudang_id'            => $request->gudang_id,
                'tanggal'              => $request->tanggal,
                'supplier_id'          => $request->supplier_id,
                'total_harga'          => $request->total_harga,
                'deskripsi'            => $request->deskripsi,
                'status'               => 0,
            ]);

            $data = [];
            foreach ($request->barang as $value) {
                $data[] = [
                    'hpurchase_order_id' => $purchase_order->id,
                    'kode_barang'        => $value['kode_barang'],
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
            $purchase_order = HeaderPurchaseOrder::find($id);
            $purchase_order->purchaseOrders()->delete();

            $purchase_order->delete();
    
            DB::commit();
        
            return response()->json(['data' => 'data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public static function show(int $id)
    {
        $purchase_order = HeaderPurchaseOrder::with('supplier','gudang','purchaseOrders.satuan','purchaseOrders.barang')->where('kode_cabang', auth()->user()->kode_cabang)->find($id);

        return response()->json(['data' => $purchase_order]);
    }

    public static function get(int $id)
    {
        $purchase_order = HeaderPurchaseOrder::select('id','nomor_purchase_order','tanggal')
                                            ->where('kode_cabang', auth()->user()->kode_cabang)
                                            ->where('supplier_id', $id)
                                            ->get();

        return response()->json(['data' => $purchase_order]);
    }

    public static function approve(int $id)
    {
        $purchase_order = HeaderPurchaseOrder::find($id)->update(['status' => 1]);
        
        return response()->json(['data' => $purchase_order]);
    }

    public static function reject(int $id)
    {
        $purchase_order = HeaderPurchaseOrder::find($id)->update(['status' => 2]);

        return response()->json(['data' => $purchase_order]);
    }
}
