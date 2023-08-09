<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Http\Requests\BarangUpdateRequest;
use App\Services\BarangService;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BarangService::index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BarangRequest  $request
     */
    public function store(BarangRequest $request)
    {
        return BarangService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(int $id)
    {
        return BarangService::show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BarangUpdateRequest  $request
     * @param  int  $id
     */
    public function update(BarangUpdateRequest $request, int $id)
    {
        return BarangService::update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(int $id)
    {
        return BarangService::delete($id);
    }

    public function search(Request $request)
    {
        return BarangService::search($request);
    }
}
