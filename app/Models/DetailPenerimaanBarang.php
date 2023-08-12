<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPenerimaanBarang extends Model
{
    use HasFactory;
    protected $table = 'dpenerimaan_barangs';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the barang associated with the DetailPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function barang(): HasOne
    {
        return $this->hasOne(Barang::class, 'kode_barang', 'kode_barang');
    }
}
