<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Releases extends Model
{
  protected $table = 'releases';

  protected $fillable = [
    'id',
    'isrc',
    'title',
    'release_date',
    'url',
    'artwork',
    'duration',
  ];
}
