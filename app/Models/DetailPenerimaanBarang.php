<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanBarang extends Model
{
    use HasFactory;
    protected $table = 'dpenerimaan_barangs';
    protected $guarded = [];
    public $timestamps = false;
}
