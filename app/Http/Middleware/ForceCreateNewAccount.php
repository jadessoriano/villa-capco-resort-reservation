<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceCreateNewAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $reservation = NULL;

        if (Auth::check()
            && ! Auth::user()->canAccessFilament()) {
            
            $reservation = Auth::user()->reservations()->orderBy('created_at', 'desc')->first();
        }

        if (is_null($reservation)) return $next($request);
       
        //check if lastest reservation is 1 year or older.
        if (Carbon::parse($reservation->reserved_date)
            <= Carbon::parse(now())->subYear()) {
            
            return redirect()->route('force.create.new.account');
        }

        return $next($request);
    }
}
