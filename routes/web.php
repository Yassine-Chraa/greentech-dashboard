<?php

use App\Http\Controllers\LockScreenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
  if (Auth::user() === null) return view('auth.login');
  else return redirect('home');
})->middleware(['auth', 'auth:sanctum']);

Auth::routes(['register' => false]);

Route::get('/home', function () {
  return view('home');
})->middleware(['lock', 'auth', 'auth:sanctum'])->name('home');
Route::get('/lockScreen', [LockScreenController::class, 'get'])->middleware(['auth', 'auth:sanctum']);
Route::post('/lockScreen', [LockScreenController::class, 'post'])->middleware(['auth', 'auth:sanctum']);
Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])->where('any', '.*')->middleware(['auth', 'lock', 'auth:sanctum']);
