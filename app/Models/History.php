<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        "statement_date",
        "release_title",
        "barcode",
        "track_downloads",
        "track_streams",
        "release_downloads",
        "track_downloads_revenue",
        "track_streams_revenue",
        "release_downloads_revenue",
        "total_revenue"
    ];
}
