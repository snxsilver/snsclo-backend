<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsoleController;
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

// Route::get('/user', [ConsoleController::class,'index']);
Route::middleware('auth:admin')->get('/user', [ConsoleController::class,'index']);
