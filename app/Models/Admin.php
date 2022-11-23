<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;

    protected $table = "admin";

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'hp', 'gender', 'birthday','password','api_token'];

    protected $hidden = ['password'];
}
