<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\GabunganController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('/mobils/trash', [MobilController::class, 'deletelist']);
    Route::get('/mobils/trash/{mobil}/restore', [MobilController::class, 'restore']);
    Route::get('/mobils/trash/{mobil}/forcedelete', [MobilController::class, 'deleteforce']);
    Route::resource('mobils', MobilController::class);
    Route::get('/pemilik/trash', [PemilikController::class, 'deletelist']);
    Route::get('/pemilik/trash/{subtype}/restore', [PemilikController::class, 'restore']);
    Route::get('/pemilik/trash/{subtype}/forcedelete', [PemilikController::class, 'deleteforce']);
    Route::resource('pemilik', PemilikController::class);
    Route::get('/gabungan', [GabunganController::class,'index']);

});