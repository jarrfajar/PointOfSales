<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HeaderReturPenerimaanBarang extends Model
{
    use HasFactory;
    protected $table = 'hretur_penerimaan_barang';
    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get all of the barangs for the HeaderPenerimaanBarang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barangs(): HasMany
    {
        return $this->hasMany(DetailReturPenerimaanBarang::class, 'header_retur_id', 'id');
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
