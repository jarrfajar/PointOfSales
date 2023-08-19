<?php

namespace App\Services;
use App\Models\StockBarang;

class StockService
{
    public $barangId;
    public $jumlah;
    public $stockBarang;

    public function __construct(int $barangId, int $jumlah) {
        $this->barangId    = $barangId;
        $this->jumlah      = $jumlah;
        $this->stockBarang = StockBarang::where('kode_cabang', auth()->user()->kode_cabang)->where('barang_id', $barangId)->first();
    }

    public static function init(int $barangId, int $jumlah)
    {
        return new self($barangId, $jumlah);
    }
    
    public function increase()
    {
        $this->stockBarang->masuk  += $this->jumlah;
        $this->stockBarang->jumlah += $this->jumlah;
        $this->stockBarang->update();
    }

    public function decrease()
    {
        $this->stockBarang->masuk  -= $this->jumlah;
        $this->stockBarang->jumlah -= $this->jumlah;
        $this->stockBarang->update();
    }
}
