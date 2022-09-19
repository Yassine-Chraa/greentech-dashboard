<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
  protected $fillable = [
    'nom',
    'nombre_produits'
  ];
  use HasFactory;
  public $timestamps = false;
}
