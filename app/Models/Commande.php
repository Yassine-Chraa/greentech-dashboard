<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'client_id',
    'fournisseur',
    'date',
    'avance',
    'total',
  ];
  public $timestamps = false;
}
