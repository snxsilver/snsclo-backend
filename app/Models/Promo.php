<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = "promo";

    protected $primaryKey='uuid';
    public $incrementing = false;
    protected $keyType='string';

    protected $fillable = [
        'uuid',
        'product',
        'discount',
        'trigger',
        'duration',
    ];
}
