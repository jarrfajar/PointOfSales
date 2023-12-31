<?php

namespace App\Http\Controllers;

use App\Services\Kasir\KasirService;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        return KasirService::index();
    }

    public function store(Request $request)
    {
        return KasirService::store($request);
    }
}
