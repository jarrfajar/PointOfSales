<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrderRequest;
use App\Services\Pembeliaan\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PurchaseOrderService::index();
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param PurchaseOrderRequest $request
     */
    public function store(PurchaseOrderRequest $request)
    {
        return PurchaseOrderService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        return PurchaseOrderService::show($id);
    }

    public function get($id)
    {
        return PurchaseOrderService::get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PurchaseOrderRequest  $request
     * @param  int  $id
     */
    public function update(PurchaseOrderRequest $request, $id)
    {
        return PurchaseOrderService::update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        return PurchaseOrderService::delete($id);
    }
    
    public function approve(int $id)
    {
        return PurchaseOrderService::approve($id);
    }
    
    public function reject(int $id)
    {
        return PurchaseOrderService::reject($id);
    }
}
