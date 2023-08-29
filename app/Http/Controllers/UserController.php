<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userProfile(){
        $books = Auth::user()->books;
        $purchasedBooks = Auth::user()->purchasedBooks()->where('payment_status', 'success')->get();
        return view('user.profile' , compact('books', 'purchasedBooks'));
    }
}
