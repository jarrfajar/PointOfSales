<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return $this->hasOne(Barang::class, 'id', 'barang_id');
    }

    /**
     * Get the satuan associated with the DetailPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function satuan(): HasOne
    {
        return $this->hasOne(Satuan::class, 'id', 'satuan_id');
    }

    /**
     * Get the penerimaanBarang that owns the DetailPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penerimaanBarang(): BelongsTo
    {
        return $this->belongsTo(HeaderPenerimaanBarang::class, 'penerimaan_barang_id', 'id');
    }
}
