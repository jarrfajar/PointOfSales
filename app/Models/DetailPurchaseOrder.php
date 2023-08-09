<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'dpurchase_order';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the purchaseOrders that owns the DetailPurchaseOrderr
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrders(): BelongsTo
    {
        return $this->belongsTo(HeaderPurchaseOrder::class, 'id', 'hpurchase_order_id');
    }

    /**
     * Get the satuan associated with the DetailPurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function satuan(): HasOne
    {
        return $this->hasOne(Satuan::class, 'id', 'satuan_id');
    }

    /**
     * Get the barang associated with the DetailPurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function barang(): HasOne
    {
        return $this->hasOne(Barang::class, 'id', 'kode_barang');
    }
}
