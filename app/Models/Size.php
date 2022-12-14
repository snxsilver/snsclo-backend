<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = "size";

    protected $primaryKey='uuid';
    public $incrementing = false;
    protected $keyType='string';

    protected $fillable = [
        'uuid',
        'product',
        'description',
    ];
}
