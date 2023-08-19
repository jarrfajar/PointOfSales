<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderPenjualan extends Model
{
    use HasFactory;
    protected $table = 'hpenjualan';
    protected $guarded = [];
    public $timestamps = false;
}
