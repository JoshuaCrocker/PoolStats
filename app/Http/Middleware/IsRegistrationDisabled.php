<?php

namespace App\Http\Middleware;

use Closure;

class IsRegistrationDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!env('ALLOW_REGISTRATION', false)) {
            return redirect('/login')
                ->with('message', 'Registration is disabled.');
        }

        return $next($request);
    }
}
