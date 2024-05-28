<?php

namespace App\Http\Controllers;

use App\Models\Releases;
use Spotify;

class ReleasesController
{
    private function searchTrack($id)
    {
        $query = Spotify::tracks($id)->get();

        return $query['tracks'][0];
    }

    private function createRelease($query)
    {
        $release = Releases::where('id', $query['id'])->first();

        if (!$release) {
            Releases::create([
                'id' => $query['id'],
                'isrc' => $query['external_ids']['isrc'],
                'title'  => $query['name'],
                'url' => $query['external_urls']['spotify'],
                'release_date' => $query['album']['release_date'],
                'duration' => $query['duration_ms'],
                'artwork' => json_encode($query['album']['images']),
                // 'artists' => array_map(function ($artist) {
                //     return [
                //         'id' => $artist['id'],
                //         'name'  => $artist['name'],
                //         'url'  => $artist['external_urls']['spotify']
                //     ];
                // }, $query['artists'])
            ]);
        }
    }

    private function getTrack($id)
    {
        $query = $this->searchTrack($id);

        if (!$query) {
            dd('no data from spotify');
        }

        $this->createRelease($query);
    }

    private function getAllReleases()
    {
        $url = env('SPOTIFY_LABEL_PLAYLIST_ID');
        $limit = 100;
        $query = Spotify::playlistTracks($url)->get();
        $total = $query['total'];
        $tracks = [];

        foreach ($query['items'] as $track) {
            $tracks[] = $track['track']['id'];
        }

        if ($total > $limit) {
            for ($i = $limit; $i <= ceil($total / $limit) * $limit; $i += $limit) {
                $query = Spotify::playlistTracks($url)->offset($i)->get()['items'];

                if (!empty($query)) {
                    foreach ($query as $track) {
                        $tracks[] = $track['track']['id'];
                    }
                } else {
                    break;
                }
            }
        }


        foreach ($tracks as $id) {
            $this->getTrack($id);
        }

        return true;
    }

    public function view()
    {
        $data = $this->getAllReleases();

        return response()->json($data);
    }
}
