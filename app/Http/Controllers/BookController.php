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
        $this->middleware('auth')->except('index' , 'show', 'indexCategory', 'indexFilters');
    }

    public function downloadBook(Book $book)
    {
        if (file_exists(storage_path('app/' .$book->pdf))) {
            return Storage::download($book->pdf);
        } else {
            return redirect()->route('welcome')->with('errorMessage','Il file PDF del libro ricercato, non è piú presente nel filesystem!');
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

    public function indexFilters(){
        return view('book.indexFilter');
    }

    public function indexCategory(Category $category){
        $books = Book::where('is_published', true)->where('category_id', $category->id)->get();
        // return view('book.indexCategory', compact('category'));
        return view('book.indexCategory', compact('category','books'));
      }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.create');
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
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('user.profile')->with('message','Libro cancellato con successo!');
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
