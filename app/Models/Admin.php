<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $table = "admin";

    protected $primaryKey='uuid';
    public $incrementing = false;
    protected $keyType='string';

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'username',
        'email',
        'hp',
        'gender',
        'birthday',
        'password',
        'role',
        'super_admin',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'super_admin'
    ];
}
