<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Mail\SendStatisticMail;
use GuzzleHttp\Client;

use App\Models\Artist;

class PayoutController extends Controller
{
    public function app(Request $request)
    {
        $user = $request->user;
        $avatar = $request->avatar;
        $balance = $request->balance;

        $value = 'payout';

        return view('layout', [
            'class' => $value,
            'content' => view($value, [
                'user' => $user,
                'avatar' => $avatar,
                'balance' => $balance
            ])->render()
        ]);
    }

    public function post(Request $request)
    {
        $user = Auth::user();
        $money = $request->money;
        $email = $request->paypal;

        $validator = Validator::make($request->all(), [
            'paypal' => 'required',
            'money' => 'required',
            'accept' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors(['payoutMessage' => 'Something went wrong. Please fill in all fields.']);
        }

        if ($money < 5) {
            return back()
                ->withErrors(['payoutMessage' => 'For a payout you must have earned at least 5€. Please try another time.']);
        }

        try {
            $query = Artist::where('user_id', $user->id)->first();
            $query->payed += $money;
            $query->save();

            $subject = '🚨 New payout request';
            $informations = 'Artist: ' . $user->name . '<br> Amount: ' . $money . '€' . '<br> PayPal: ' . $email;
            Mail::to(env('MAIL_DEBUG'))->send(new SendStatisticMail($subject, $informations));

            return redirect()->back()->with('payoutSuccess', 'Your payout has been successfully requested.');
        } catch (Exception $e) {
            return back()
                ->withErrors(['payoutMessage' => 'Something went wrong. Please try again.']);
        }
    }
}
