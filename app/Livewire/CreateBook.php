<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use App\Models\Category;
use App\Mail\ReviewRequest;
use Livewire\WithFileUploads;
use App\Models\GeneratedImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Jobs\GenerateOpenAiCoverImageJob;

class CreateBook extends Component
{
    use WithFileUploads;

    public $title, $description, $pdf, $promptToken, $cover, $book, $style, $subject, $ambience, $otherDetails, $mainColor, $selectedCategory, $editMode, $oldTitle, $oldPdf, $topic, $didacticsId, $nonFictionId;
    public $step = 1;
    public $price = 0;
    public $askReview=false;
    public $generatedImage;
    public $isGeneratingImage = false;
    public $isDidactics_nonFiction = false;
    public $isOtherCategory = false;

    protected $queryString = ['step'];

    // Stili suggeriti pre compilati
    public $styles = ['Gothic','Disney','Storybook','3D render','Kodachrome','Steampuk','Realistic','Realismo','Futuristico','Pencil drawing'];

    protected $rules = [
        'title' => 'required',
        'description' => 'required',
        'pdf' => 'required_if:oldPdf,!=,null',
        'selectedCategory' => 'required',
        'cover' => 'required',
        'price' => 'required|min:0|numeric',
    ];

    protected $messages = [
        'required' => 'Il campo :attribute é richiesto',
        'required_if' => 'Il campo :attribute é richiesto',
        'file' => 'Il campo :attribute deve essere un file',
        'mimes' => 'Il campo :attribute deve essere di tipo pdf',
        'min' => 'Il campo :attribute deve essere minimo :min',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    //Recupero nella mount degli id delle categorie
  public function mount($book = null){

        $this->step = 1;

        if($book) {
            $this->editMode = true;

            $this->book = $book;
            $this->title = $book->title;
            $this->oldTitle = $book->title; 
            $this->description = $book->description; 
            $this->cover = $book->cover;
            $this->selectedCategory = $book->category->id;        
            $this->price = $book->price;
            $this->oldPdf = $book->pdf;       
        }

        $didactics = Category::where('name','Didattica')->first();
        $nonFiction = Category::where('name','Saggistica')->first();

        $this->didacticsId = $didactics->id;
        $this->nonFictionId = $nonFiction->id;
    }

    public function generatePromptTokenForCategory($category){
        $default=config('app.imagegen_default_prompt');

        $finalPrompt = "$default , use style: $this->style";
    
        if ($category == $this->didacticsId || $category == $this->nonFictionId) {
            $finalPrompt .= ", the book subject is: $this->subject , the book main topic is: $this->topic";
        } else {
            $finalPrompt .= ", the book main character is: $this->subject , the book main ambience is: $this->topic";
        }
    
        if($this->otherDetails){
            $finalPrompt .= ", other details here: $this->otherDetails";
        }
    
        if($this->mainColor){
            $finalPrompt .= ", use main color: $this->mainColor";
        }
    
        return $finalPrompt;
    }

    public function saveBook()
    {
        // Validate
        $this->validate();
        
        if($this->editMode){
            // Aggiornamento book
           $this->book->update([
                'title' => $this->title,
                'description' => $this->description,
                'pdf' => $this->pdf ? $this->pdf->store('public/files') : $this->book->pdf,
                'cover'=> $this->cover ? $this->cover : $this->book->cover,
                'is_published'=> !$this->askReview,
                'price' => $this->price,
                'category_id'=> $this->selectedCategory,
                'review_status' => $this->askReview ? 'pending' : 'completed',
                ]
            );

            $message = "Libro modificato correttamente";

        }else{
            // Creazione book
            $this->book = Book::create(
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

            $message = "Libro memorizzato correttamente";
        }

        //invio mail al revisore con job
        if($this->askReview){
            Mail::to('revisor@aulabook.com')->queue(new ReviewRequest($this->book));
            return redirect()->route('welcome')->with('message','Libro inviato per la recesione correttamente');
        } 

        return redirect()->route('welcome')->with('message',$message); 
    }

    public function generate(){

        $this->validate([
            'topic' => 'required|max:1000',
            'style'=>'required|max:1000',
            'subject'=>'required',
        ]);

        // $default=config('app.imagegen_default_prompt');
        // $this->promptToken = " $default , 
        //                         use style: $this->style, 
        //                         the book subject is: $this->subject , 
        //                         the book main ambience is: $this->ambience , 
        //                         other details here: $this->otherDetails, 
        //                         the book main color is: $this->mainColor";
        //$this->cover = Book::generateImage($this->cover, $this->promptToken);
        $this->promptToken = $this->generatePromptTokenForCategory($this->selectedCategory);

        if($this->generatedImage){
            Storage::disk('public')->delete($this->generatedImage->image);
            $this->generatedImage->delete();
            $this->generatedImage = null;
        }

        // Creazione del modello GeneratedImage
        $this->generatedImage = GeneratedImage::create([
            'prompt'=>$this->promptToken,
        ]);

        dispatch(new GenerateOpenAiCoverImageJob($this->generatedImage));
        $this->isGeneratingImage = true;
    }

    public function checkGeneratedImage(){
        if($this->generatedImage->error){
            $this->isGeneratingImage = false;
            session()->flash('errorMessage', $this->generatedImage->error);
            $this->generatedImage = null;
            return;
        }
        if($this->generatedImage->image){
            $this->cover = $this->generatedImage->image;
            $this->isGeneratingImage = false;
        }
    }

    //funzione per il cambio degli step
    public function changeStep($newStep)
    {
        $this->step = $newStep;
    }

    // Funzioone di controllo degli step next e validazione dei campi per ogni step
    public function nextStep(){
        if ($this->step == 1) {
            $this->validate([
                'title' => 'required',
                'description' => 'required',
                'pdf' => 'required_if:oldPdf,!=,null',
                'selectedCategory' => 'required',
                'price' => 'required|min:0|numeric',
            ], $this->messages);

            $this->step++;
            return;
        } 
        
        if ($this->step == 2){
            if(!$this->cover  && !$this->editMode){
                session()->flash('error', "Devi prima generare la copertina del libro");
                $this->validate([
                    'style'=>'required|max:1000',
                    'subject'=>'required',
                    'topic'=>'required|max:1000',
                ]);
                return;
            }

            $this->step++;
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
        // Categorie valide per la seconda versione del prompt
        $didacticsAndNonFictionPrompt = [$this->didacticsId, $this->nonFictionId];

        if (in_array($this->selectedCategory, $didacticsAndNonFictionPrompt)) {
            $this->isDidactics_nonFiction = true;
        }else{
            $this->isDidactics_nonFiction = false;
        }

        return view('livewire.create-book');
    }
}
