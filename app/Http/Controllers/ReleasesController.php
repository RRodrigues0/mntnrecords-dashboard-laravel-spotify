<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use App\Models\Release;
use App\Models\Artist;
use App\Models\User;
use Spotify;


class ReleasesController extends Controller
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

    private function checkIfReleaseExistThisMonth()
    {
        $releases = Release::first();
        $date = date('Y-m');
        $date = $date . '-01 00:00:00';

        if ($releases && $releases->created_at >= $date) {
            return true;
        }
        return false;
    }

    private function saveReleasesAsJsonInDatabase($data)
    {
        $json = json_encode($data);
        $releases = new Release();
        $releases->json = $json;
        $releases->save();
        $this->saveArtists($data);
    }

    private function createPassword($length = 8)
    {
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++)
            $str .= $chars[random_int(0, $max)];

        return $str;
    }

    private function createUser($data)
    {
        $password = $this->createPassword();

        $newUser = new User();
        $newUser->username = $data['name'];
        $newUser->password = Hash::make($password);
        $newUser->save();
    }

    private function saveArtists($data)
    {
        foreach ($data as $release) {
            $artists = $release[0]['artists'];

            foreach ($artists as $artist) {
                $query = Artist::where('id', $artist['id'])->first();

                if (!$query) {
                    $this->createUser($artist);
                    $user = User::where('username', $artist['name'])->first();

                    $newArtist = new Artist();
                    $newArtist->id = $artist['id'];
                    $newArtist->user_id = $user['id'];
                    $newArtist->save();
                }
            }
        }
    }

    // public function getReleases()
    // {
    //     $releases = Releases::first();

    //     return response()->json([
    //         'id' => $releases->id,
    //         'length' => count(json_decode($releases->json)),
    //         'created_at' => $releases->created_at,
    //         'updated_at' => $releases->updated_at,
    //         'items' => json_decode($releases->json)
    //     ]);
    // }

    public function view()
    {
        // if ($this->checkIfReleaseExistThisMonth()) {
        //     return response()->json('Releases already saved in Database!');
        // }
        $data = $this->getAllReleases();
        $this->saveReleasesAsJsonInDatabase($data);

        // $releases = Releases::first();
        // $data = json_decode($releases->json);
        // $this->saveArtists($data);

        return response()->json('Releases saved in Database!');
    }
}
