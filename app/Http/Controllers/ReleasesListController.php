<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spotify;
use App\Models\Split;
use App\Models\Income;
use App\Models\History;

class ReleasesListController extends Controller
{
    private function searchTrackByISRC($isrc)
    {
        $query = Spotify::searchTracks('isrc:' . $isrc, ['track'])->get();

        return $query['tracks'];
    }

    private function getTrackDataFromSpotify($isrc)
    {
        if (strpos($isrc, '-') !== false) {
            $isrc = str_replace("-", "", $isrc);
        }

        if (session()->has('trackData')) {
            $trackData = session('trackData');
            if (isset($trackData[$isrc])) {
                return $trackData[$isrc];
            }
        }

        $data = $this->searchTrackByISRC($isrc);

        $trackData = session('trackData', []);
        $trackData[$isrc] = $data;
        session(['trackData' => $trackData]);

        return $data;
    }

    private function getAllIncomesForRelease($release)
    {
        $income = 0;
        $id = $release['id'];

        $query = Split::where("release_id", $id)->first();
        $query = Income::where("split_id", $query->id)->orderBy("statement_date", 'DESC')->get();

        foreach ($query as $item) {
            $income += floatval($item->income);
        }

        return $income;
    }

    private function getAllDownloadsForRelease($release)
    {
        $downloads = 0;
        $barcode = $release['barcode'];

        $query = History::where("barcode", $barcode)->orderBy("statement_date", 'DESC')->get();

        foreach ($query as $item) {
            $downloads += floatval($item->track_downloads);
        }

        return $downloads;
    }

    private function getAllStreamsForRelease($release)
    {
        $streams = 0;
        $barcode = $release['barcode'];

        $query = History::where("barcode", $barcode)->orderBy("statement_date", 'DESC')->take(2)->get();

        foreach ($query as $item) {
            $streams += floatval($item->track_streams);
        }

        return $streams;
    }

    public function app(Request $request)
    {
        $user = $request->user;
        $avatar = $request->avatar;
        $balance = $request->balance;
        $releases = $request->releases;



        foreach ($releases as $key => $release) {
            if (!$release['isrc']) {
                continue;
            }

            $data = $this->getTrackDataFromSpotify($release['isrc']);
            $income = $this->getAllIncomesForRelease($release);
            $downloads = $this->getAllDownloadsForRelease($release);
            $streams = $this->getAllStreamsForRelease($release);

            $releases[$key]['image'] = $data['items'][0]['album']['images'][2]['url'] ?? '';
            $releases[$key]['date'] = $data['items'][0]['album']['release_date'] ?? '';
            $releases[$key]['income'] = $income;
            $releases[$key]['downloads'] = $downloads;
            $releases[$key]['streams'] = $streams;
        }

        $value = 'releases';

        return view('layout', [
            'class' => $value,
            'content' => view($value, [
                'user' => $user,
                'avatar' => $avatar,
                'balance' => $balance,
                'releases' => $releases
            ])->render()
        ]);
    }
}
