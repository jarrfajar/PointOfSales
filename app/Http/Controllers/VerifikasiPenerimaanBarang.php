<?php

namespace App\Http\Controllers;

use App\Services\Pembeliaan\VerfikasiPenerimaanBarangService;
use Illuminate\Http\Request;

class VerifikasiPenerimaanBarang extends Controller
{
    public function index()
    {
        return VerfikasiPenerimaanBarangService::index();
    }

    public function verifikasi(Request $request, string $nomor_bapb)
    {
        return VerfikasiPenerimaanBarangService::verifikasi($request, $nomor_bapb);
    }
}
