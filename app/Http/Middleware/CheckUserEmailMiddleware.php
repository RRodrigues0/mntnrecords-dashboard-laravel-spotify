<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && in_array($user->email, ['raphael.rodrigues@outlook.de', 'raphael@mntnrecords.com'])) {
            return $next($request);
        }

        return redirect('/'); // Redirect to home or any other page
    }
}
