<?php

namespace App\Http\Middleware;

use finfo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use App\Models\History;
use App\Models\Split;
use App\Models\Artist;
use App\Models\Release;

class UserDataMiddleware
{
    private function sumIncome($data, $payed)
    {
        $sum = 0 - $payed;

        foreach ($data as $item) {
            $money = $item->income;
            $sum += floatval($money);
        }

        return number_format($sum, 2);
    }

    private function getMonth()
    {
        $query = History::orderBy('statement_date', 'desc')->first();

        if ($query === null || !$query->exists()) {
            return (new \DateTime(now()))->format('m');
        }

        $date = new \DateTime($query->month);

        return $date->format('m');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $releases = [];
        $histories = [];

        $artist = Artist::where('user_id', Auth::user()->id)->first();
        $avatar = $artist->avatar;
        $artist_id = $artist->id;
        $splits = Split::where('artist_id', $artist_id)->get();

        $payed = $artist->payed;

        if ($avatar) {
            $blob = $avatar;
            $base64 = base64_encode($blob);

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($blob);

            $avatar = 'data:' . $mimeType . ';base64,' . $base64;
        }

        foreach ($splits as $split) {
            $query = Release::where('id', $split->release_id)->whereNotNull('barcode')->first();

            if ($query === null || !$query->exists()) {
                continue;
            }

            $releases[] = $query->toArray();

            $query = History::where('barcode', $query->barcode);

            if ($query === null || !$query->exists()) {
                continue;
            }

            $histories[] = $query->get()->toArray();
        }

        $balance = $this->sumIncome($splits, $payed);
        $lastMonth = $this->getMonth();

        $request->merge([
            'user' => Auth::user(),
            'avatar' => $avatar,
            'balance' => $balance,
            'histories' => $histories,
            'splits' => $splits->toArray(),
            'releases' => $releases,
            'lastMonth' => $lastMonth
        ]);

        return $next($request);
    }
}
