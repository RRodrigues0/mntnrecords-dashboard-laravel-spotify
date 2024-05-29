<?php

namespace App\Http\Middleware;

use App\Models\Releases;
use App\Http\Controllers\ArtistsController;

use Closure;
use Spotify;

class ReleasesMiddleware
{
  public function handle($request, Closure $next)
  {
    $request->merge([
      'user' => $user,
      'balance' => $balance,
      'releases' => $releases,
      'lastMonth' => $lastMonth
    ]);

    return $next($request);
  }
}
