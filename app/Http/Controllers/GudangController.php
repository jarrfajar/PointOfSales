<?php

namespace App\Http\Controllers;

use App\Http\Requests\GudangRequest;
use App\Services\GudangService;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GudangService::index();
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param GudangRequest $request
     */
    public function store(GudangRequest $request)
    {
        return GudangService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        return GudangService::show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GudangRequest  $request
     * @param  int  $id
     */
    public function update(GudangRequest $request, $id)
    {
        return GudangService::update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        return GudangService::delete($id);
    }

    public function search(Request $request)
    {
        return GudangService::search($request);
    }
}
