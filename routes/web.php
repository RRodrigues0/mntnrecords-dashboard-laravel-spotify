<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

use App\Mail\SendForgotMail;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddFileController;
use App\Http\Controllers\CreateReleaseController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\ReleasesController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\PayoutController;

use App\Models\Histories;
use App\Models\Users;
use App\Models\Releases;
use App\Models\HistoriesReleaseLinks;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/webhooks/statistic', [StatisticController::class, 'app'])->middleware(['verifyWebhookKey', 'modelStatistic']);

// Route::get('/debug', [StatisticController::class, 'app']);

// Route::get('/storage-link', function () {
//     Artisan::call('storage:link');
//     return 'Symbolischer Link erstellt';
// });

Route::get('/login', function () {
    return view('layout', [
        'class' => 'login',
        'content' => view('login')->render()
    ]);
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    $credentials['blocked'] = 0;
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
    function generatePassword() {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        $charactersLength = strlen($characters);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        return $password;
    }

    $email = $request->only('email');
    $query = Users::where('email', $email)->first();

    if ($query) {
        $password = generatePassword();
        $query->password = Hash::make($password);
        $query->save();

        Mail::to($query->email)->send(new SendForgotMail($query->firstname, $password));
    }

    return back()
        ->withErrors(['send' => true]);
});

Route::get('/dashboard', [DashboardController::class, 'app'])->middleware(['auth', 'verified', 'userData'])->name('dashboard');

Route::get('/releases', [ReleasesController::class, 'app'])->middleware(['auth', 'verified', 'userData'])->name('releases');

Route::get('/profile', [ProfileController::class, 'app'])->middleware(['auth', 'verified', 'userData'])->name('profile');
Route::post('/profile', [ProfileController::class, 'post'])->middleware(['auth', 'verified', 'userData']);

Route::get('/payout', [PayoutController::class, 'app'])->middleware(['auth', 'verified', 'userData'])->name('payout');
Route::post('/payout', [PayoutController::class, 'post'])->middleware(['auth', 'verified', 'userData']);

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

