<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use GuzzleHttp\Client;
use App\Models\Users;

class ProfileController extends Controller
{
    public function app(Request $request)
    {
        $user = $request->user;
        $balance = $request->balance;

        $value = 'profile';

        return view('layout', [
            'class' => $value,
            'content' => view($value, [
                'user' => $user,
                'balance' => $balance
            ])->render()
        ]);
    }

    public function post(Request $request)
    {
        $form = $request->input('id');

        if ($form == "avatarForm") {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpg,jpeg|max:2048',
            ]);
            if ($validator->fails()) {
                return back()
                    ->withErrors(['avatarMessage' => 'Something went wrong. Please try a smaller or different image format.']);
            }

            $imageName = time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->move(public_path('storage/uploads'), $imageName);

            $imageUrl = public_path('storage/uploads') . '/' . $imageName;
            $client = new Client();

            try {
                $response = $client->post(env('STRAPI_URL') . '/api/upload/', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('STRAPI_API_KEY'),
                    ],
                    'multipart' => [
                        [
                            'name' => 'files',
                            'contents' => fopen($imageUrl, 'r'),
                            'filename' => $imageName,
                        ],
                    ],
                ]);

                $image = json_decode($response->getBody(), true);

                $response = $client->put(env('STRAPI_URL') . '/api/users/' . $user->id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('STRAPI_API_KEY'),
                    ],
                    'json' => [
                        'avatar' => $image[0]['id'],
                    ],
                ]);

                return redirect()->back()->with('avatarSuccess', 'Picture changed successfully.');
            } catch (Exception $e) {
                return back()
                    ->withErrors(['avatarMessage' => 'Something went wrong. Please try a smaller or different image format.']);
            }
        }

        if ($form == "passForm") {
            $user = Auth::user();
            $oldPassword = $request->input('oldPassword');
            $newPassword = $request->input('newPassword');
            $confirmPassword = $request->input('confirmPassword');

            if (!password_verify($oldPassword, $user->password)) {
                return back()
                    ->withErrors(['passwordMessage' => 'The old password you entered is incorrect.']);
            }

            if ($newPassword !== $confirmPassword) {
                return back()
                    ->withErrors(['passwordMessage' => 'The confirmation password does not match.']);
            }

            $query = Users::where('id', $user->id)->first();
            $query->password = Hash::make($newPassword);
            $query->save();

            return redirect()->back()->with('passwordSuccess', 'Password changed successfully');
        }

        // return redirect('/profile');
    }
}
