<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeProduits extends Model
{
  use HasFactory;

  protected $fillable = [
    'commande_id',
    'produit_id',
    'prix',
    'quantite',
  ];
  public $timestamps = false;
}
