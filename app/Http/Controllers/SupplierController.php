<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function search(Request $request)
    {
        return SupplierService::search($request);
    }
}
