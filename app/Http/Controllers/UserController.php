<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userProfile(){
        $user = Auth::user();
        $books = $user->books;
        $purchasedBooks = $user->purchasedBooks;
        return view('user.profile' , compact('books', 'purchasedBooks'));
    }
}
