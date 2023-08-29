<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsRevisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    $user = Auth::user();
    if( $user && ($user->isRevisor() || $user->isAdmin())){
        return $next($request);
    }
    return redirect(route('welcome'))->with('message', 'Area riservata ai revisori');
}
