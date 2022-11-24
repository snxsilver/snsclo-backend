<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AdminController::class,'register']);
Route::post('/login', [AdminController::class,'login']);
Route::post('/logout', [AdminController::class,'logout']);

// Route::get('/user', [ConsoleController::class,'index']);
Route::middleware('auth:user')->get('/user', [ConsoleController::class,'index']);

Route::post('/register_user', [UserController::class,'register']);
Route::post('/login_user', [UserController::class,'login']);
Route::post('/logout_user', [UserController::class,'logout']);