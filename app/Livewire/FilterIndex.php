<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;

class FilterIndex extends Component
{
    public $minPrice;
    public $maxPrice;
    public $search;
    public $categoryChecked = [];
    public $orderValue = 'createAsc';

    //Utilizzo della query string per la visualizzazione dei parametri nell'url
    protected $queryString = ['search', 'categoryChecked', 'minPrice', 'maxPrice'];
    
    //Funzione di pulizia della query string
    public function clearQueryString()
    {
        $this->reset(['minPrice', 'maxPrice', 'search', 'categoryChecked']);
        $this->orderValue = 'createAsc';
    }

    //Nella funzione render aggiorniamo i dati dinamicamente a seconda della selezione
    public function render(){

        //Se viene fatta la ricerca testuale
        if($this->search){
            $searched = Book::search($this->search)->where('is_published', true)->get();
        } else {
            $searched = Book::where('is_published', true);
        }

        //ricerca per categoria con query sulla collection
        if($this->categoryChecked){
            $searched = $searched->whereIn('category_id', $this->categoryChecked);
        }

        //ricerca per prezzo minimo con query sulla collection
        if($this->minPrice){
            $searched = $searched->where('price', '>=', $this->minPrice);
        }

        //ricerca per prezzo massimo con query sulla collection
        if($this->maxPrice){
            $searched = $searched->where('price', '<=', $this->maxPrice);
        }

        if(! $this->search){
            $searched = $searched->get();
        } 
      
        //switch per ordinamento 
        switch ($this->orderValue) {
            case 'priceAsc':
                $searched = $searched->sortBy('price');
                break;

            case 'priceDesc':
                $searched = $searched->sortByDesc('price');
                break;
            
            case 'alfaOrderAsc':
                $searched = $searched->sortBy('title');
                break;

            case 'alfaOrderADesc':
                $searched = $searched->sortByDesc('title');
                break;

            case 'createDesc':
                $searched = $searched->sortByDesc('created_at');
                break;
            
            case 'createAsc':
                $searched = $searched->sortBy('created_at');
                break;
        }

        return view('livewire.filter-index', ['searched'=> $searched]);
    }
}
