<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private function getHistoryFromStrapi()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . env('STRAPI_API_KEY'),
        ])->get(env('STRAPI_URL') . '/api/histories?populate=deep')->object();
    }

    private function extractData($releases, $history, $attribute, $limit)
    {
        $numbers = collect();

        foreach ($history->data as $data) {
            $id = $data->attributes->release->data->id;
            $date = $data->attributes->date;

            if ($releases->has($id)) {
                $dateKey = substr($date, 0, 7);
                if ($numbers->has($dateKey)) {
                    $numbers[$dateKey]->attributes->$attribute += $data->attributes->$attribute;
                } else {
                    $numbers->put($dateKey, $data);
                }
            }
        }

        $sorted = $numbers->sortByDesc(function ($item, $key) {
            return $item->attributes->date;
        });

        $values = $sorted->slice(0, $limit)->pluck("attributes.$attribute")->toArray();
        $values = array_pad($values, $limit, 0);

        return $values;
    }

    public function app(Request $request)
    {
        // $history = $this->getHistoryFromStrapi();
        $user = $request->user;
        $balance = $request->balance;
        $releases = $request->releases;
        $lastMonth = $request->lastMonth;

        $earnings = implode(";", $this->extractData($releases, $history, 'income', 7) ?? array_fill(0, 7, 0));
        $lastReachesStreams = implode(";", $this->extractData($releases, $history, 'streams', 7) ?? array_fill(0, 7, 0));
        $lastReachesDownloads = implode(";", $this->extractData($releases, $history, 'downloads', 7) ?? array_fill(0, 7, 0));
        $efficiency = implode(";", $this->extractData($releases, $history, 'streams', 2) ?? [100, 100]);

        $value = 'dashboard';

        return view('layout', [
            'class' => $value,
            'lastMonth' => $lastMonth,
            'content' => view($value, [
                'user' => $user,
                'balance' => $balance,
                'earnings' => $earnings,
                'lastReachesStreams' => $lastReachesStreams,
                'lastReachesDownloads' => $lastReachesDownloads,
                'efficiency' => $efficiency,
            ])->render()
        ]);
    }
}
