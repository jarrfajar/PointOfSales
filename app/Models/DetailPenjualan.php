<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPenjualan extends Model
{
    use HasFactory;
    protected $table = 'dpenjualan';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the headerPenjualan that owns the DetailPenjualan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(HeaderPenjualan::class, 'hpenjualan_id', 'id');
    }

    /**
     * Get the barang associated with the DetailPurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function barang(): HasOne
    {
        return $this->hasOne(Barang::class, 'id', 'barang_id');
    }
}
