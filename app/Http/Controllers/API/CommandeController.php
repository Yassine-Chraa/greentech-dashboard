<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandeProduits;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{

  public function updateStock($commande_id, $produit_id, $quantite)
  {
    $produit = Produit::findOrFail($produit_id);
    $commandes = DB::select("SELECT type FROM commandes WHERE commandes.id = {$commande_id}");
    if ($commandes[0]->type == 'achat') $produit->stock = $produit->stock + $quantite;
    else $produit->stock = $produit->stock - $quantite;
    $produit->save();
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->get('page') != 0) {
      $commandes = DB::table('commandes')
        ->select('commandes.id', 'nom', 'fournisseur', 'date', 'commandes.avance', 'total', 'dette')
        ->join('clients', 'commandes.client_id', '=', 'clients.id')
        ->where($request->get('type') == 'vente'?'nom':'fournisseur', 'LIKE', $request->get('keyword') . '%')
        ->where('commandes.type', '=', $request->get('type'))
        ->where('commandes.date', $request->get('date') == "" ? 'LIKE' : '=', $request->get('date'))
        ->orderBy($request->get('orderTarget'), $request->get('order'))
        ->paginate(6);
    } else {
      $commandes = DB::select("SELECT commandes.id,type,nom,fournisseur,date,dette FROM commandes INNER JOIN clients ON commandes.client_id=clients.id WHERE commandes.type = '{$request->get('type')}' ORDER BY date LIMIT 6");
    }
    return response()->json($commandes);
  }
  public function getProduits($id)
  {
    $produits = DB::select("SELECT nom,date,commande_produits.id,commande_produits.prix,quantite FROM commandes INNER JOIN commande_produits ON commande_produits.commande_id = commandes.id  INNER JOIN produits ON commande_produits.produit_id = produits.id WHERE commande_produits.commande_id= {$id}");
    return response()->json($produits);
  }
  public function getDaySales($date)
  {
    $ventes = DB::select("SELECT SUM(prix*quantite) AS ventes FROM commandes,commande_produits WHERE commandes.id = commande_produits.commande_id  AND date = '{$date}' AND  commandes.type = 'vente'");
    return response()->json($ventes);
  }
  public function getMonthSales($date)
  {
    $date = str_replace('-', '', $date);
    $date = str_split($date, 4);
    $ventes = DB::select("SELECT  SUM(prix*quantite) AS ventes FROM commandes,commande_produits WHERE commandes.id = commande_produits.commande_id AND MONTH(date) = '{$date[1]}' AND YEAR(date) = '{$date[0]}' AND commandes.type = 'vente'");
    return response()->json($ventes);
  }
  public function getEvryDaySales($date)
  {
    $date = str_replace('-', '', $date);
    $date = str_split($date, 4);
    $ventes = DB::select("SELECT DAY(date) as day, SUM(prix*quantite) AS total FROM commandes,commande_produits WHERE  MONTH(date) = '{$date[1]}' AND YEAR(date) = '{$date[0]}' AND commandes.id = commande_produits.commande_id AND commandes.type = 'vente' GROUP BY day");
    $achats = DB::select("SELECT DAY(date) as day, SUM(prix*quantite) AS total FROM commandes,commande_produits WHERE  MONTH(date) = '{$date[1]}' AND YEAR(date) = '{$date[0]}' AND commandes.id = commande_produits.commande_id AND commandes.type = 'achat' GROUP BY day");
    return response()->json(['ventes' => $ventes, 'achats' => $achats]);
  }
  public function getTotal(Request $request, $type)
  {
    $totale = DB::select("SELECT SUM(prix*quantite) AS total FROM commande_produits,commandes WHERE commande_produits.commande_id = commandes.id AND commandes.type = '{$type}' AND YEAR(commandes.date) = {$request->get('annee')}");
    return response()->json($totale);
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
      'fournisseur' => 'required',
      'date' => 'required|date',
    ]);
    $clients = DB::select("SELECT id FROM clients WHERE clients.nom LIKE '{$request->get('nom')}'");
    if ($request->get('isNew')) {
      if ($clients == null) {
        $request->validate([
          'nom' => 'required|min:4',
        ]);
        $newClient = new Client([
          'nom' => $request->get('nom'),
          'numero' => '',
        ]);
        $newClient->save();
        $clients = DB::select("SELECT id FROM clients WHERE clients.nom LIKE '{$request->get('nom')}'");
        $newCommande = new Commande([
          'type' => $request->get('type'),
          'client_id' => $clients[0]->id,
          'fournisseur' => $request->get('fournisseur'),
          'date' => $request->get('date'),
        ]);

        $newCommande->save();
        return response()->json(['message' => 'Commande stored']);
      } else {
        return response(["error" => "Le client exist!"], 422);
      }
    } else {
      if ($clients == null) {
        return response(["error" => "Le client n'exist pas"], 422);
      } else {
        $newCommande = new Commande([
          'type' => $request->get('type'),
          'client_id' => $clients[0]->id,
          'fournisseur' => $request->get('fournisseur'),
          'date' => $request->get('date'),
        ]);

        $client = Client::findOrFail($clients[0]->id);
        $client->save();

        $newCommande->save();
        return response()->json(['message' => 'Commande stored']);
      }
    }
  }
  public function storeProduit(Request $request)
  {
    $request->validate([
      'nom' => 'required',
      'prix' => 'required|numeric|min:0',
      'quantite' => 'required|integer|min:0',
    ]);
    $produits = DB::select("SELECT id FROM produits WHERE produits.nom LIKE '{$request->get('nom')}' LIMIT 1");
    if ($produits != null) {
      $produit = Produit::findOrFail($produits[0]->id);
      $commande = Commande::findOrFail($request->get('commande_id'));
      if ($commande->type == 'achat' || $request->get('quantite') <= $produit->stock) {
        $newCommandeProduit = new CommandeProduits([
          'produit_id' => $produits[0]->id,
          'commande_id' => $request->get('commande_id'),
          'prix' => $request->get('prix'),
          'quantite' => $request->get('quantite'),
        ]);

        $commande->total += $newCommandeProduit->prix * $newCommandeProduit->quantite;
        $client = client::findOrFail($commande->client_id);
        $client->dette += $newCommandeProduit->prix * $newCommandeProduit->quantite;
        $client->save();

        $commande->save();
        $newCommandeProduit->save();
        $this->updateStock($request->get('commande_id'), $produits[0]->id, $request->get('quantite'));
        return response(['message' => 'produit stored']);
      } else {
        return response(['error' => 'Le stock est insuffisant pour cette commande'], 422);
      }
    } else {
      return response(["error" => "Le produit n'exist pas"], 422);
    }
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
    $commande = Commande::findOrFail($id);
    $request->validate([
      'nom' => 'required',
      'fournisseur' => 'required',
      'date' => 'required|date',
      'avance' => 'required|numeric|min:0',
    ]);

    $clients = DB::select("SELECT id FROM clients WHERE clients.nom LIKE '{$request->get('nom')}'");
    if (sizeof($clients) == 0) {
      if ($request->get('isNew')) {
        $request->validate([
          'nom' => 'required|min:4',
        ]);
        $newClient = new Client([
          'nom' => $request->get('nom'),
          'numero' => '',
        ]);
        $newClient->save();
      } else {
        return response(["error" => "Le client n'exist pas"], 422);
      }
    } else {
      if ($request->get('isNew')) {
        return response(["error" => "Le client exist!"], 422);
      }
    }
    $clients = DB::select("SELECT id FROM clients WHERE clients.nom LIKE '{$request->get('nom')}'");

    /***** If you change commande client *****/
    if ($commande->client_id != $clients[0]->id) {
      $total = $commande->total;
      $avance = $commande->avance;
      $pre_client = Client::findOrFail($commande->client_id);
      $pre_client->dette -= $total - $avance;
      $pre_client->save();
    }
    /***** END *****/
    $commande->client_id = $clients[0]->id;
    $commande->date = $request->get('date');
    $delta = $commande->avance - $request->get('avance');
    $commande->avance = $request->get('avance');

    $client = client::findOrFail($commande->client_id);

    if (isset($total)) $client->dette += $total - $avance;
    else $client->dette += $delta;

    $client->save();

    $commande->save();

    return response()->json(['message' => 'Commande updated']);
  }

  public function updateProduit(Request $request, $id)
  {
    $commandeProduit = CommandeProduits::findOrFail($id);
    $commande = Commande::findOrFail($commandeProduit->commande_id);
    $produit = Produit::findOrFail($commandeProduit->produit_id);
    if ($commande->type == 'achat' ||  $request->get('quantite') - $commandeProduit->quantite <= $produit->stock) {
      $request->validate([
        'prix' => 'required|numeric|min:0',
        'quantite' => 'required|integer|min:0',
      ]);

      $commandeProduit->prix = $request->get('prix');
      $quantite =  $request->get('quantite') - $commandeProduit->quantite;
      $commandeProduit->quantite = $request->get('quantite');

      $commandeProduit->save();
      $res = DB::select("SELECT SUM(prix*quantite) as montant from commande_produits WHERE commande_id = {$commandeProduit->commande_id}");
      $commande->total = $res[0]->montant;

      $client = Client::findOrFail($commande->client_id);
      $commande->save();

      $res = DB::select("SELECT SUM(total) as total,SUM(avance) as avance from commandes WHERE client_id = {$client->id}");
      $client->dette = $res[0]->total - $res[0]->avance;
      $client->save();

      $this->updateStock($commandeProduit->commande_id, $commandeProduit->produit_id, $quantite);

      return response()->json(['message' => 'Product updated']);
    } else {
      return response(['error' => 'Le stock est insuffisant pour cette commande'], 422);
    }
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $commande = Commande::findOrFail($id);
    $produits = DB::select("SELECT produit_id,quantite FROM produits,commande_produits WHERE commande_produits.produit_id = produits.id  AND commande_produits.commande_id = {$id}");

    foreach ($produits as $produit) {
      $this->updateStock($commande->id, $produit->produit_id, -$produit->quantite);
    }
    $client = Client::findOrFail($commande->client_id);
    $client->dette -= $commande->total - $commande->avance;
    $client->save();

    $commande->delete();


    return response()->json(['message' => 'Commande deleted']);
  }

  public function deleteProduit($id)
  {
    $produit = CommandeProduits::findOrFail($id);

    $commande = Commande::findOrFail($produit->commande_id);
    $commande->total -= $produit->prix * $produit->quantite;

    $client = Client::findOrFail($commande->client_id);
    $client->dette -= $produit->prix * $produit->quantite;
    $client->save();

    $commande->save();

    $this->updateStock($produit->commande_id, $produit->produit_id, -$produit->quantite);
    $produit->delete();


    return response()->json(['message' => 'Product deleted']);
  }
}
