<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  use HasFactory;
  protected $fillable = [
    'nom',
    'numero',
    'dette',
  ];
  public $timestamps = false;
}
