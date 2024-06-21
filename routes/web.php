<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReleasesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\StatisticController;

use App\Http\Middleware\UserDataMiddleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/login', function () {
    return view('layout', [
        'class' => 'login',
        'content' => view('login')->render()
    ]);
})->name('login');

Route::get('/admin/releases/update', [ReleasesController::class, 'get']);
Route::get('/admin/statistic/upload', [StatisticController::class, 'get']);
Route::post('/admin/statistic/upload', [StatisticController::class, 'post']);

Route::get('/login', function () {
    return view('layout', [
        'class' => 'login',
        'content' => view('login')->render()
    ]);
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    $remember = $request->filled('remember');

    if (Auth::attempt($credentials, $remember)) {
        return redirect()->intended('/dashboard');
    } else {
        return back()
            ->withErrors(['message' => true]);
    }
});

Route::get('/forgot', function () {
    return view('layout', [
        'class' => 'forgot',
        'content' => view('forgot')->render()
    ]);
})->name('forgot');

Route::post('/forgot', function (Request $request) {
    function createPassword($length = 8)
    {
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' .
            '0123456789`-=~!@#$%^&*()_+,./<>?;:[]{}\|';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++)
            $str .= $chars[random_int(0, $max)];

        return $str;
    }

    $email = $request->input('email');
    $user = User::where('email', $email)->first();

    if ($user) {
        $password = createPassword();
        $user->password = Hash::make($password);
        $user->save();

        Mail::to($user->email)->send(new SendForgotMail($user->firstname, $password));
    }

    return back()->withErrors(['send' => true]);
});

Route::middleware(['auth', 'verified', UserDataMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'app'])->name('dashboard');
    Route::get('/releases', [ReleasesController::class, 'app'])->name('releases');
    Route::get('/profile', [ProfileController::class, 'app'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'post']);
    Route::get('/payout', [PayoutController::class, 'app'])->name('payout');
    Route::post('/payout', [PayoutController::class, 'post']);
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Route::fallback(function () {
    return redirect('/dashboard');
});
