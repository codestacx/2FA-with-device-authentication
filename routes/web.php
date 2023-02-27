<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('index');
Route::match(['get','post'],'/register',[AuthController::class,'register']);
Route::match(['get','post'],'/2fa',[AuthController::class,'askFor2FAAuthentication'])->name('google2fa');
Route::post('login',[AuthController::class,'login'])->name('login');

