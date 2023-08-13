<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StockBarang extends Model
{
    use HasFactory;
    protected $table = 'stock_barang';
    protected $guarded = [];
    public $timestamps = false;
    public $appends = ['status_str'];

    /**
     * Get the barang associated with the StockBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function barang(): HasOne
    {
        return $this->hasOne(Barang::class, 'id', 'barang_id');
    }

    /**
     * Get the gudang associated with the StockBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gudang(): HasOne
    {
        return $this->hasOne(Gudang::class, 'id', 'gudang_id');
    }

    public function getStatusStrAttribute()
    {
        if ($this->jumlah > $this->batas_max) {
            return 'Berlebih';
        }

        if ($this->jumlah != 0 && $this->jumlah < $this->batas_min) {
            return 'Peringatan';
        }

        if ($this->jumlah  >= $this->batas_min && $this->jumlah <=  $this->batas_max) {
            return 'Normal';
        }

        if ($this->jumlah == 0) {
            return 'Habis';
        }
    }
}
