<?php

namespace App\Services;
use App\Models\SerialNumber;

class SerialNumberService
{
    public static function genereteNumber(string $kode_cabang, string $kode_urut)
    {
        $serial = SerialNumber::where('kode_cabang', $kode_cabang)->where('kode_urut', $kode_urut)->first();
        $serial->increment('nomor_urut', 1);
        $serial->fresh();

        $nextNumber = str_pad(0, 4, '0', STR_PAD_LEFT);
        return $serial->kode_cabang.$serial->kode_urut.date('Ymd').$nextNumber.$serial->nomor_urut;
    }
}
