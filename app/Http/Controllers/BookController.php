<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index' , 'show', 'indexCategory');
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
        $books = Book::where('is_published', true)->paginate(6);//paginate bootstrap
        return view('book.index', compact('books'));
    }

    public function indexCategory(Category $category){
        return view('book.indexCategory', compact('category'));
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
        return view('book.edit' , compact('book'));
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
        $book->delete();

        return redirect()->route('user.profile')->with('success','Libro cancellato con successo!');
    }

    //funzione per la pubblicazione del libro
    public function publish(Book $book)
    {
        if( !$book->isBookAuthor() ){
            return redirect()->back()->with('message','Non sei l\'autore di questo libro, non puoi pubblicarlo!');
        }

        $book->setAccepted(true);
        return redirect()->back()->with('message','Libro pubblicato con successo');
    }

    //funzione per nascondere il libro
    public function unpublish(Book $book)
    {
        if( !$book->isBookAuthor() ){
            return redirect()->back()->with('message','Non sei l\'autore di questo libro, non puoi rimuovere la pubblicazione!');
        }
        
        $book->setAccepted(false);
        return redirect()->back()->with('message','Libro rimosso corretamente');
    }

    //funzione collegata alla ricerca full text iniziale
    public function searchBooks(Request $request){
        $books = Book::search($request->searched)->where('is_published' , true)->paginate(10);
        return view('book.index' , compact('books'));
    }
}
