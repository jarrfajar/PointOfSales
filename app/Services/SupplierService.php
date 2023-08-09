<?php

namespace App\Services;
use App\Models\Supplier;

class SupplierService
{
    public static function search(object $request)
    {
        $supplier = Supplier::when($request->search, function($query) use ($request) {
                        return $query->where('nama', 'LIKE', "%$request->search%");
                    })
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get();
                          
        
        return response()->json(['data' => $supplier]);
    }
}
