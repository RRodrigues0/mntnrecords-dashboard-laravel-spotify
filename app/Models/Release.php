<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    protected $fillable = [
        'isrc',
        'barcode',
        'title',
        'percentage',
        'income',
        'reserved'
    ];
}
