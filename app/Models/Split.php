<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Split extends Model
{
    protected $fillable = [
        'release_id',
        'artist_id',
        'percentage',
        'income'
    ];
}
