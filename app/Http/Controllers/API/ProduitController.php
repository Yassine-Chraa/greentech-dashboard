<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->get('page') != 0) {
      $produits = DB::table('produits')
        ->select('produits.id', 'produits.nom', 'categories.nom as categorie', 'categorie_id', 'prix', 'stock')
        ->join('categories', 'produits.categorie_id', '=', 'categories.id')
        ->where('produits.nom', 'LIKE', $request->get('keyword') . '%')
        ->orderBy($request->get('orderTarget'), $request->get('order'))
        ->paginate(8);
    } else {
      $produits = DB::table('produits')
        ->select('id', 'nom', 'categorie_id', 'prix', 'stock')
        ->where('categorie_id', 'LIKE', $request->get('categorie_id'))
        ->get();
    }

    return response()->json($produits);
  }
  public function getProduitVentes($categorie_id)
  {
    $query = "SELECT produits.nom,SUM(quantite) AS quantite FROM produits,commande_produits,commandes WHERE commande_produits.produit_id = produits.id AND commande_produits.commande_id = commandes.id AND commandes.fournisseur = 'GREENTECH' AND produits.categorie_id = {$categorie_id} GROUP BY produits.nom";
    $vente = DB::select($query);
    return response()->json($vente);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'nom' => 'required',
      'prix' => 'required|numeric|min:0',
      'stock' => 'required|numeric|min:0',
    ]);
    $newProduit = new Produit([
      'nom' => $request->get('nom'),
      'categorie_id' => $request->get('categorie_id'),
      'prix' => $request->get('prix'),
      'stock' => $request->get('stock'),
    ]);

    $newProduit->save();

    $categorie = Categorie::findOrFail($request->get('categorie_id'));
    $categorie->nombre_produits = $categorie->nombre_produits + 1;
    $categorie->save();

    return response()->json(['message' => 'Produit stored']);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $produit = Produit::findOrFail($id);
    $request->validate([
      'nom' => 'required',
      'prix' => 'required|numeric|min:0',
      'stock' => 'required|numeric|min:0',
    ]);

    $produit->nom = $request->get('nom');
    $produit->categorie_id = $request->get('categorie_id');
    $produit->prix = $request->get('prix');
    $produit->stock = $request->get('stock');

    $produit->save();

    return response()->json(['message' => 'Product updated']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $produit = Produit::findOrFail($id);
    $produit->delete();

    $categorie = Categorie::findOrFail($produit->categorie_id);
    $categorie->nombre_produits = $categorie->nombre_produits - 1;
    $categorie->save();

    return response()->json(['message' => 'Product deleted']);
  }
}
