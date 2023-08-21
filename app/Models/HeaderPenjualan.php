<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HeaderPenjualan extends Model
{
    use HasFactory;
    protected $table = 'hpenjualan';
    protected $guarded = [];
    public $timestamps = false;

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
     * Get all of the sales for the HeaderPenjualan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'hpenjualan_id', 'id');
    }
}
