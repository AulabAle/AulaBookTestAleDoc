<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateBook extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $pdf;


    protected $rules = [
        'title' => 'required',
        'description' => 'required',
        'pdf' => 'required|file|mimes:pdf',
    ];

    protected $messages = [
        'required' => 'Il campo :attribute é richiesto',
        'file' => 'Il campo :attribute deve essere un file',
        'mimes' => 'Il campo :attribute deve essere di tipo pdf',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function saveBook()
    {
        // Validate
        $this->validate();

        // Creazione book
        Book::create(
            [
            'title' => $this->title,
            'description' => $this->description,
            'pdf' =>$this->pdf->store('public/files'),
            'user_id' => Auth::user()->id, 
            ]
        );

        session()->flash('message','eBook inserito correttamente');

        // Reset dei campi del form
        $this->reset();

    }

    public function render()
    {
        return view('livewire.create-book');
    }
}
