<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Message extends Model
{
  use HasFactory,HasApiTokens;

  protected $fillable = [
    'nom',
    'numero',
    'email',
    'sujet',
    'contenu',
  ];
  public $timestamps = false;
}
