<?php

namespace App\Services;
use App\Models\StockBarang;

class StockService
{
    public $barang_id;
    public $jumlah;
    public $stock_barang;

    public function __construct(int $barang_id, int $jumlah) {
        $this->barang_id    = $barang_id;
        $this->jumlah       = $jumlah;
        $this->stock_barang = StockBarang::where('kode_cabang', auth()->user()->kode_cabang)->where('barang_id', $barang_id)->first();
    }

    public static function init(int $barang_id, int $jumlah)
    {
        return new self($barang_id, $jumlah);
    }
    
    public function increase()
    {
        $this->stock_barang->masuk  += $this->jumlah;
        $this->stock_barang->jumlah += $this->jumlah;
        $this->stock_barang->update();
    }

    public function decrease()
    {
        $this->stock_barang->masuk  -= $this->jumlah;
        $this->stock_barang->jumlah -= $this->jumlah;
        $this->stock_barang->update();
    }
}
