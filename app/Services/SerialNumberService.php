<?php

namespace App\Services;
use App\Models\InvoiceNumber;
use App\Models\SerialNumber;

class SerialNumberService
{
    public static function genereteNumber(string $kodeCabang, string $kodeUrut)
    {
        $serial = SerialNumber::where('kode_cabang', $kodeCabang)->where('kode_urut', $kodeUrut)->first();
        $serial->increment('nomor_urut', 1);
        $serial->fresh();

        $nextNumber = str_pad(0, 4, '0', STR_PAD_LEFT);
        return $serial->kode_cabang.$serial->kode_urut.date('Ymd').$nextNumber.$serial->nomor_urut;
    }

    public static function createInvoice()
    {
        $prefix  = date('Y-m-d');
        $invoice = InvoiceNumber::where('kode_cabang', auth()->user()->kode_cabang)->first();

        if ($invoice) {
            if (date('Y-m-d') == $invoice->date) {
                $invoice->increment('number');
                $invoice->fresh();
                $nextNumber = str_pad($invoice->number, 5, '0', STR_PAD_LEFT);
                return auth()->user()->kode_cabang . '-' . $prefix . '-' . $nextNumber;
            }

            $invoice->update([
                'number' => 1,
                'date'   => date('Y-m-d'),
            ]);

            return auth()->user()->kode_cabang . '-' . $prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
        }

        InvoiceNumber::create([
            'kode_cabang' => auth()->user()->kode_cabang,
            'number'      => 1,
            'date'        => date('Y-m-d'),
        ]);

        return auth()->user()->kode_cabang . '-' . $prefix . '-' . str_pad(1, 5, '0', STR_PAD_LEFT);
    }
}
