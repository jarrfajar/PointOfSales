<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HeaderPurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'hpurchase_order';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get all of the purchaseOrders for the HeaderPurchaseOrderr
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(DetailPurchaseOrder::class, 'hpurchase_order_id', 'id');
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
}
