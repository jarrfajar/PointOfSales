<?php

namespace App\Http\Controllers;

use App\Http\Requests\SatuanRequest;
use App\Services\SatuanService;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SatuanService::index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SatuanRequest  $request
     */
    public function store(SatuanRequest $request)
    {
        return SatuanService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(int $id)
    {
        return SatuanService::show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SatuanRequest  $request
     * @param  int  $id
     */
    public function update(SatuanRequest $request, int $id)
    {
        return SatuanService::update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(int $id)
    {
        return SatuanService::delete($id);
    }

    public function search(Request $request)
    {
        return SatuanService::search($request);
    }
}
