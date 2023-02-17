<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});


Route::group(['middleware' => ['auth:sanctum', 'abilities:manage-dashboard']], function () {
  Route::resource('users', UserController::class);
  Route::resource('clients', ClientController::class);
  Route::resource('categories', CategorieController::class);
  Route::resource('messages', MessageController::class);
  Route::resource('produits', ProduitController::class);

  Route::get('clients/produits/{id}', [ClientController::class, 'getProduits'])->name('clients.produits');


  Route::apiResource('commandes', CommandeController::class);

  Route::put('commandes/produits/{commande}', [CommandeController::class, 'updateProduit'])->name('commande.updateProduit');
  Route::delete('commandes/produits/{commande}', [CommandeController::class, 'deleteProduit'])->name('commande.deleteProduit');
  Route::post('commandes/produits/', [CommandeController::class, 'storeProduit'])->name('commande.storeProduit');
});
Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::get('produits/ventes/{categorie_id}', [ProduitController::class, 'getProduitVentes'])->name('produits.ventes');
  Route::get('produits', [ProduitController::class, 'index'])->name('produits.index');
  Route::get('categories', [CategorieController::class, 'index'])->name('categories.index');

  Route::get('commandes', [CommandeController::class, 'index'])->name('commandes.index');
  Route::get('commandes/produits/{commande}', [commandeController::class, 'getProduits'])->name('commande.produits');
  Route::get('commandes/daySales/{date}', [CommandeController::class, 'getDaySales'])->name('commande.daySales');
  Route::get('commandes/monthSales/{date}', [CommandeController::class, 'getMonthSales'])->name('commande.monthSales');
  Route::get('commandes/chartSales/{date}', [CommandeController::class, 'getEvryDaySales'])->name('commande.chartSales');
  Route::get('commandes/total/{type}', [CommandeController::class, 'getTotal'])->name('commande.total');
});
