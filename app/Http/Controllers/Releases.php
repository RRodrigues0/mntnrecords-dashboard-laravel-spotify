<?php

namespace App\Http\Controllers;

use Spotify;

class Releases
{
    private function searchTrack($id)
    {
        $query = Spotify::tracks($id)->get();

        return $query['tracks'][0];
    }

    private function getTrack($id)
    {
        $query = $this->searchTrack($id);

        $data[] = [
            "id" => $query["id"],
            "isrc" => $query["external_ids"]["isrc"],
            "name"  => $query["name"],
            "url" => $query["external_urls"]["spotify"],
            "release_date" => $query["album"]["release_date"],
            "duration_ms" => $query["duration_ms"],
            "artwork" => $query["album"]["images"],
            "artists" => array_map(function ($artist) {
                return [
                    "id" => $artist["id"],
                    "name"  => $artist["name"],
                    "url"  => $artist["external_urls"]["spotify"]
                ];
            }, $query["artists"])
        ];

        return $data;
    }

    private function getAllReleases()
    {
        $url = env("SPOTIFY_LABEL_PLAYLIST_ID");
        $limit = 100;
        $query = Spotify::playlistTracks($url)->get();
        $total = $query['total'];
        $tracks = [];
        $data = [];

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
            $data[] = $this->getTrack($id);
        }

        return $data;
    }

    public function view()
    {
        $data = $this->getAllReleases();

        return response()->json($data);
    }
}
