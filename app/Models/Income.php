<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'statement_date',
        'income',
        'split_id'
    ];
}
