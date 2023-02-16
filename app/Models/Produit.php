<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Produit extends Model
{
  use HasFactory,HasApiTokens;

  protected $fillable = [
    'nom',
    'categorie_id',
    'prix',
    'stock',
  ];
  public $timestamps = false;
}
