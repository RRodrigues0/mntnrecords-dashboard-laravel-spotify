<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Split;
use App\Models\Income;
use App\Models\History;

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

    private function getLastTwoMonthStreams($releases)
    {
        $streams = [];

        for ($i = 0; $i < 2; $i++) {
            $sum = 0;

            foreach ($releases as $release) {
                $barcode = $release['barcode'];

                $query = History::where("barcode", $barcode)->orderBy("statement_date", 'DESC')->take(2)->get();

                if (!$query->has($i)) {
                    continue;
                }

                $sum += floatval($query[$i]->track_streams);
            }

            $streams[] = $sum;
        }

        return $streams;
    }

    private function getLastSevenMonthStreams($releases)
    {
        $streams = [];

        for ($i = 0; $i < 7; $i++) {
            $sum = 0;

            foreach ($releases as $release) {
                $barcode = $release['barcode'];

                $query = History::where("barcode", $barcode)->orderBy("statement_date", 'DESC')->take(2)->get();

                if (!$query->has($i)) {
                    continue;
                }

                $sum += floatval($query[$i]->track_streams);
            }

            $streams[] = $sum;
        }

        return $streams;
    }
    private function getLastSevenMonthDownloads($releases)
    {
        $downloads = [];

        for ($i = 0; $i < 7; $i++) {
            $sum = 0;

            foreach ($releases as $release) {
                $barcode = $release['barcode'];

                $query = History::where("barcode", $barcode)->orderBy("statement_date", 'DESC')->take(2)->get();

                if (!$query->has($i)) {
                    continue;
                }

                $sum += floatval($query[$i]->track_downloads);
            }

            $downloads[] = $sum;
        }

        return $downloads;
    }

    public function app(Request $request)
    {
        $user = $request->user;
        $avatar = $request->avatar;
        $balance = $request->balance;
        $lastMonth = $request->lastMonth;
        $releases = $request->releases;

        $earnings = implode(";", $this->getAllIncomesByMonth($releases));
        $lastReachesStreams = implode(";", $this->getLastSevenMonthStreams($releases));
        $lastReachesDownloads = implode(";", $this->getLastSevenMonthDownloads($releases));
        $efficiency = implode(";", $this->getLastTwoMonthStreams($releases));

        $value = 'dashboard';

        return view('layout', [
            'class' => $value,
            'lastMonth' => $lastMonth,
            'content' => view($value, [
                'user' => $user,
                'avatar' => $avatar,
                'balance' => $balance,
                'earnings' => $earnings ?? implode(";", array_fill(0, 7, 0)),
                'lastReachesStreams' => $lastReachesStreams ?? implode(";", array_fill(0, 7, 0)),
                'lastReachesDownloads' => $lastReachesDownloads ?? implode(";", array_fill(0, 7, 0)),
                'efficiency' => $efficiency ?? implode(";", array_fill(0, 2, 100)),
            ])->render()
        ]);
    }
}
