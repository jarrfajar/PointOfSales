<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    use HasFactory;
    protected $table = 'urut';
    protected $guarded = [];
    public $timestamps = false;
}
