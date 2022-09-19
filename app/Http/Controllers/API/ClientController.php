<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $clients = DB::table('clients')->select('id', 'nom', 'numero', 'dette')
      ->where('nom', 'LIKE', $request->get('keyword') . '%')
      ->orderBy($request->get('orderTarget'), $request->get('order'))
      ->paginate(6);
    return response()->json($clients);
  }

  public function getProduits($id)
  {
    $produits = DB::select("SELECT nom,commande_produits.prix,quantite,date FROM commande_produits,commandes,produits WHERE
    commande_produits.commande_id = commandes.id AND commande_produits.produit_id = produits.id AND commandes.client_id = {$id} ORDER BY date ASC");

    return response()->json($produits);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
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
      'nom' => 'required|min:3',
      'numero' => 'required|digits:10',
    ]);
    $newClient = new Client([
      'nom' => $request->get('nom'),
      'numero' => $request->get('numero'),
    ]);

    $newClient->save();

    return response()->json(['message' => 'Client stored']);
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
    $client = Client::findOrFail($id);
    $request->validate([
      'nom' => 'required|min:3',
      'numero' => 'required|digits:10',
    ]);

    $client->nom = $request->get('nom');
    $client->numero = $request->get('numero');

    $client->save();

    return response()->json(['message' => 'Client updated']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $client = Client::findOrFail($id);
    $client->delete();

    return response()->json(['message' => 'Client deleted']);
  }
}
