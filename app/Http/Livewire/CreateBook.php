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

    public $title, $description, $pdf, $promptToken, $cover, $book, $style, $character, $ambience, $otherDetails, $mainColor;
    public $step = 1;

    // Stili suggeriti pre compilati
    public $styles = ['Gothic','Disney','Storybook','3D render','Kodachrome','Steampuk','Realistic','Realismo','Futuristico','Pencil drawing'];

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
            'cover'=>$this->cover ? $this->cover : '/img/default.png',
            ]
        );
            
        session()->flash('message','eBook inserito correttamente');
        
        // Reset dei campi del form
        $this->reset();
            
    }

    public function generate(){
        $this->validate([
            'ambience' => 'required|max:1000',
            'style'=>'required|max:1000',
        ]);
        $default=env('DEFAULT_PROMPT');
        $this->promptToken = "$default , $this->style, $this->character , $this->ambience , $this->otherDetails , $this->mainColor"; 
        $this->cover = Book::generateImage($this->cover, $this->promptToken);
    }

    //funzione per il cambio degli step
    public function changeStep($newStep)
    {
        $this->step = $newStep;
    }

    //Funzione di controllo degli step next e validazione dei campi per ogni step
    public function nextStep(){
        if ($this->step == 1) {
            $this->validate([
                'title' => 'required',
                'description' => 'required',
                'pdf' => 'required|file|mimes:pdf',
            ]);
            $this->changeStep(2);
        } elseif ($this->step == 2){
            $this->changeStep(3);
        }
        
    }
    //funzione di controllo degli step prew
    public function prevStep(){
        if ($this->step == 3) {
            $this->changeStep(2);
        } elseif ($this->step == 2){
            $this->changeStep(1);
        }
    }

    public function render()
    {
        return view('livewire.create-book');
    }
}
