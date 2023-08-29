<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home() {
        //ordine decrescente
        $books = Book::where('is_published', true)->orderBy('created_at','DESC')->get()->take(6);
        return view('welcome', compact('books'));
    }
}
