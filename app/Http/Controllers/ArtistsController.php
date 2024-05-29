<?php

namespace App\Http\Controllers;

use App\Models\Artists;

class ArtistsController
{
  public function createArtist($query)
  {
    $artist = Artists::where('id', $query['id'])->first();

    return response()->json(true);
  }
}
