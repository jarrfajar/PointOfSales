<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HeaderPenerimaanBarang extends Model
{
    use HasFactory;
    protected $table = 'hpenerimaan_barangs';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get all of the barangs for the HeaderPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(DetailPenerimaanBarang::class, 'penerimaan_barang_id', 'id');
    }
    
    /**
     * Get the supplier associated with the HeaderPurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    /**
     * Get the gudang associated with the HeaderPurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gudang(): HasOne
    {
        return $this->hasOne(Gudang::class, 'id', 'gudang_id');
    }

    /**
     * Get the purchaseOrder associated with the HeaderPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(HeaderPurchaseOrder::class, 'id', 'purchase_order_id');
    }
}
