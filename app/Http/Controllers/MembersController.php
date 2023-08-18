<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Services\MembersService;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return MembersService::index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MemberRequest  $request
     */
    public function store(MemberRequest $request)
    {
        return MembersService::store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        return MembersService::show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  MemberRequest  $request
     * @param  int  $id
     */
    public function update(MemberRequest $request, $id)
    {
        return MembersService::update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        return MembersService::delete($id);
    }

    public function search(Request $request)
    {
        return MembersService::search($request);
    }
}
