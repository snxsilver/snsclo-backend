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

Route::post('/register_super', [AdminController::class, 'register_super_admin']);
Route::post('/reset', [AdminController::class, 'reset_super_admin']);

Route::post('/register', [AdminController::class, 'register']);
Route::post('/login', [AdminController::class, 'login']);

Route::get('/unauthenticated', function () {
  return response()->json([
    'message' => 'Unauthenticated'
], 401);
})->name('unauthenticated');

Route::group(['middleware' => 'auth:admin'], function () {
  Route::get('/logout', [ConsoleController::class, 'logout']);
  Route::get('/admin', [ConsoleController::class, 'index']);
  Route::get('/bodrex', [ConsoleController::class, 'bodrex']);
});

// Route::get('/getid', [AdminController::class,'get_id']);

// Route::middleware('auth:admin-login')->get('/get_admin', [AdminController::class,'getUser']);

Route::get('/users', [ConsoleController::class, 'index']);

Route::middleware('auth:user')->get('/user', [ConsoleController::class, 'index']);

Route::post('/register_user', [UserController::class, 'register']);
Route::post('/login_user', [UserController::class, 'login']);
Route::get('/logout_user', [UserController::class, 'logout']);
