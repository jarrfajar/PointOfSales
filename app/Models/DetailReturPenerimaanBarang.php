<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailReturPenerimaanBarang extends Model
{
    use HasFactory;
    protected $table = 'dretur_penerimaan_barang';
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
     * Get the penerimaanBarang that owns the DetailPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function returnBarang(): BelongsTo
    {
        return $this->belongsTo(HeaderReturPenerimaanBarang::class, 'header_retur_id', 'id');
    }
}
