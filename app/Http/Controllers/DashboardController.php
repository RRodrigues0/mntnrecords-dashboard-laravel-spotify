<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Split;
use App\Models\Income;

class DashboardController extends Controller
{
    private function getAllIncomesByMonth($releases)
    {
        $numbers = [];

        for ($i = 0; $i < 7; $i++) {
            $sum = 0;
            foreach ($releases as $release) {
                $id = $release['id'];

                $query = Split::where("release_id", $id)->first();
                $query = Income::where("split_id", $query->id)->orderBy("statement_date", 'DESC')->take(7)->get();

                if (!$query->has($i)) {
                    continue;
                }

                $sum += floatval($query[$i]->income);
            }

            $numbers[] = $sum;
        }

        return $numbers;
    }

    public function app(Request $request)
    {
        $user = $request->user;
        $balance = $request->balance;
        $lastMonth = $request->lastMonth;
        $releases = $request->releases;
        $histories = $request->histories;
        $splits = $request->splits;

        // dd($balance, $splits, $releases, $histories);
        $earnings = implode(";", $this->getAllIncomesByMonth($releases) ?? array_fill(0, 7, 0));
        // $lastReachesStreams = implode(";", $this->extractData($releases, $histories, 'streams', 7) ?? array_fill(0, 7, 0));
        // $lastReachesDownloads = implode(";", $this->extractData($releases, $histories, 'downloads', 7) ?? array_fill(0, 7, 0));
        // $efficiency = implode(";", $this->extractData($releases, $histories, 'streams', 2) ?? [100, 100]);

        $value = 'dashboard';

        return view('layout', [
            'class' => $value,
            'lastMonth' => $lastMonth,
            'content' => view($value, [
                'user' => $user,
                'balance' => $balance,
                'earnings' => $earnings,
                'lastReachesStreams' => $lastReachesStreams ?? implode(";", array_fill(0, 7, 0)),
                'lastReachesDownloads' => $lastReachesDownloads ?? implode(";", array_fill(0, 7, 0)),
                'efficiency' => $efficiency ?? implode(";", array_fill(0, 7, 0)),
            ])->render()
        ]);
    }
}
