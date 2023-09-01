<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserCanDownload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        // Verifica se l'utente è autenticato
        if (!Auth::check()) {
            return redirect('/login');
        }

        //recupero il book dalla rotta
        $route = Route::current();
        $book = $route->parameter('book');
       

        $isAdmin = Auth::user()->isAdmin(); 
        $isRevisor = Auth::user()->isRevisor();
        $userHasPurchasedThisBook = Auth::user()->purchasedBooks->contains('book_id', $book->id);
        $userIsTheAuthorOfThisBook = $book->user->id == Auth::id();

        if( $userIsTheAuthorOfThisBook || $userHasPurchasedThisBook || $isRevisor || $isAdmin ){
            return $next($request); // ok sei autorizzato a scaricare questo book pdf
        }
        
        return redirect(route('welcome'))->with('message', 'Non puoi scaricare il book');
    }
}
