<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index' , 'show');
    }

    public function downloadBook(Book $book)
    {
        if (file_exists(storage_path('app/' .$book->pdf))) {
            return Storage::download($book->pdf);
        } else {
            abort(404); // File non trovato
        }
    }

    public function viewPdf(Book $book) {
        return view('book.viewPdf', compact('book'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::paginate(6);//paginate bootstrap
        return view('book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('book.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
