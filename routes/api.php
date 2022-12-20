<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsoleController;
use App\Http\Controllers\HomeController;
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

  Route::get('/admin', [ConsoleController::class, 'admin']);
  Route::post('/admin_add', [ConsoleController::class, 'admin_add']);
  Route::post('/admin_reset', [ConsoleController::class, 'admin_reset']);
  Route::post('/admin_block', [ConsoleController::class, 'admin_block']);
  Route::post('/admin_delete', [ConsoleController::class, 'admin_delete']);

  Route::post('/profile_update', [ConsoleController::class, 'profile_update']);
  Route::get('/get_profile', [ConsoleController::class, 'get_profile']);
  Route::post('/change_password', [ConsoleController::class, 'change_password']);

  Route::get('/product', [ConsoleController::class, 'product']);
  Route::post('/get_product', [ConsoleController::class, 'get_product']);
  Route::post('/product_category', [ConsoleController::class, 'product_category']);
  Route::post('/product_add', [ConsoleController::class, 'product_add']);
  Route::post('/product_edit', [ConsoleController::class, 'product_edit']);
  Route::post('/product_archieve', [ConsoleController::class, 'product_archieve']);
  Route::post('/product_delete', [ConsoleController::class, 'product_delete']);

  Route::get('/category', [ConsoleController::class, 'category']);
  Route::post('/get_category', [ConsoleController::class, 'get_category']);
  Route::post('/category_product', [ConsoleController::class, 'category_product']);
  Route::post('/category_add', [ConsoleController::class, 'category_add']);
  Route::post('/category_edit', [ConsoleController::class, 'category_edit']);
  Route::post('/category_delete', [ConsoleController::class, 'category_delete']);

  Route::get('/promo', [ConsoleController::class, 'promo']);
  Route::post('/get_promo', [ConsoleController::class, 'get_promo']);
  Route::post('/promo_add', [ConsoleController::class, 'promo_add']);
  Route::post('/promo_edit', [ConsoleController::class, 'promo_edit']);
  Route::post('/promo_delete', [ConsoleController::class, 'promo_delete']);
});

// Route::get('/getid', [AdminController::class,'get_id']);

// Route::middleware('auth:admin-login')->get('/get_admin', [AdminController::class,'getUser']);

Route::get('/product_home', [HomeController::class, 'product_home']);
Route::get('/product_slider', [HomeController::class, 'product_slider']);

Route::middleware('auth:user')->get('/user', [ConsoleController::class, 'index']);

Route::post('/register_user', [UserController::class, 'register']);
Route::post('/login_user', [UserController::class, 'login']);
Route::get('/logout_user', [UserController::class, 'logout']);
