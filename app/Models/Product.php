<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "product";

    protected $primaryKey='uuid';
    public $incrementing = false;
    protected $keyType='string';

    protected $fillable = [
        'uuid',
        'sampul0',
        'sampul1',
        'sampul2',
        'sampul3',
        'sampul4',
        'sampul5',
        'sampul6',
        'sampul7',
        'sampul8',
        'title',
        'price',
        'description',
        'weight',
    ];
}
