<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use App\Mail\ReviewRequest;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CreateBook extends Component
{
    use WithFileUploads;

    public $title, $description, $pdf, $promptToken, $cover, $book, $style, $subject, $ambience, $otherDetails, $mainColor, $selectedCategory;
    public $step = 1;
    public $price = 0;
    public $askReview=false;

    // Stili suggeriti pre compilati
    public $styles = ['Gothic','Disney','Storybook','3D render','Kodachrome','Steampuk','Realistic','Realismo','Futuristico','Pencil drawing'];

    protected $rules = [
        'title' => 'required',
        'description' => 'required',
        'pdf' => 'required|file|mimes:pdf',
        'selectedCategory' => 'required',
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
        $book = Book::create(
        [
            'title' => $this->title,
            'description' => $this->description,
            'pdf' =>$this->pdf->store('public/files'),
            'user_id' => Auth::user()->id,
            'cover'=>$this->cover ? $this->cover : '/img/default.png',
            'price' => $this->price,
            'category_id' => $this->selectedCategory,
            'is_published'=>!$this->askReview,
            'review_status' => $this->askReview ? 'pending' : 'completed',
            ]
        );

        //invio mail al revisore con job
        if($this->askReview){
            Mail::to('revisor@aulabook.com')->queue(new ReviewRequest($book));
            return redirect()->route('welcome')->with('success','Libro inviato per la recesione correttamente');
        } 
            
        session()->flash('message','eBook inserito correttamente');
        
        // Reset dei campi del form
        $this->reset();
            
    }

    public function generate(){
        $this->validate([
            'ambience' => 'required|max:1000',
            'style'=>'required|max:1000',
            'subject'=>'required',
        ]);
        $default=env('DEFAULT_PROMPT');
        $this->promptToken = " $default , 
                                use style: $this->style, 
                                the book subject is: $this->subject , 
                                the book main ambience is: $this->ambience , 
                                other details here: $this->otherDetails, 
                                the book main color is: $this->mainColor";
        $this->cover = Book::generateImage($this->cover, $this->promptToken);
    }

    //funzione per il cambio degli step
    public function changeStep($newStep)
    {
        $this->step = $newStep;
    }

    // Funzione di controllo degli step next e validazione dei campi per ogni step
    public function nextStep(){
        if ($this->step == 1) {
            $this->validate([
                'title' => 'required',
                'description' => 'required',
                'pdf' => 'required',
                'selectedCategory' => 'required',
            ], $this->messages);

            $this->step++;
            return;
        } 
        
        if ($this->step == 2){
                $this->validate([
                    'ambience' => 'required|max:1000',
                    'style'=>'required|max:1000',
                    'subject'=>'required',
                ]);
            $this->step++;
            return;
        }
    }

    //funzione di controllo degli step prew
    public function prevStep(){
        if ($this->step == 3) {
            $this->step--;
        } elseif ($this->step == 2){
            $this->step--;
        }
    }

    public function render()
    {
        return view('livewire.create-book');
    }
}
