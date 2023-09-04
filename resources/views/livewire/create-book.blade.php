<div>
    
    @if(session()->has('errorMessage'))
        <div class="d-flex justify-content-center my-2 alert alert-danger">
            {{session('errorMessage')}}
        </div>
    @endif

    @if(session()->has('message'))
        <div class="d-flex justify-content-center my-2 alert alert-success">
            {{session('message')}}
        </div>
    @endif

    <h1 class="text-center mb-5 ">
        {{ $editMode ? "Modifica il libro : $oldTitle" : "Pubblica il tuo libro" }}
    </h1>
       
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link {{ $step === 1 ? 'active' : '' }}" wire:click="changeStep(1)" >Upload</button>
            <button class="nav-link {{ $step === 2 ? 'active' : '' }}" wire:click="changeStep(2)" {{ $title ? '' : 'disabled' }}>Cover</button>
            <button class="nav-link {{ $step === 3 ? 'active' : '' }}" wire:click="changeStep(3)" {{ $cover ? '' : 'disabled' }}>Salvataggio</button>
        </div>
    </nav>
        <div class="tab-content my-3" id="nav-tabContent">
            {{-- Form inserimento dati libro --}}
            <div class="{{ $step !== 1 ? 'd-none' : '' }}">
                <form wire:submit="saveBook">         
                    {{-- input titolo --}}
                    <div class="mb-3">
                        <label id="titleId" class="form-label">Titolo*</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" for="titleId">
                        @error('title')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                        
                    </div>
                    {{-- input descrizione --}}
                    <div class="mb-3">
                        <label id="descriptionId" class="form-label">Descrizione del Libro*</label>
                        <textarea id="" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" wire:model="description" for="descriptionId"></textarea>
                        @error('description')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- input categoria --}}
                    <div class="mb-3">
                        <label for="categoryId">{{$editMode ? 'Modifica la categoria' : 'Categoria *'}}</label>
                        <select wire:model='selectedCategory' id="categoryId" class="form-control @error('selectedCategory') is-invalid @enderror">
                            <option value="">Scegli la categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                        </select>
                        @error('selectedCategory')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- input price --}}
                    <div class="mb-3">
                        <label id="price" class="form-label">{{$editMode ? 'Modifica il prezzo €' : 'Inserisci il prezzo € *'}}</label>
                        <input type="number" step="0.1" class="form-control @error('price') is-invalid @enderror" wire:model="price" for="price">
                        @error('price')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- input pdf --}}
                        <div class="mb-3">
                            <label id="pdfId" class="form-label">{{$editMode ? 'Aggiorna il tuo PDF' : 'Carica il tuo PDF *'}}</label>
                            <input type="file" class="form-control @error('pdf') is-invalid @enderror" wire:model="pdf"  accept=".pdf" for="pdfId">
                            @error('pdf')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" wire:model="oldPdf">
                        @if($editMode)
                            <a class="text-center" href="{{route('book.download', compact('book'))}}" >Scarica la versione precedente</a>
                        @endif

                    </form>
                </div>
        </div>
        
            {{-- Form generazione immagine --}}  
            <div class="{{ $step !== 2 ? 'd-none' : '' }}">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10 pt-2">
                            <h2 class="text-center">Descrivi la tua immagine di copertina</h2>
                        </div>
                    </div>
                </div>
                <form wire:submit="generate">
                    <div class="mb-3">
                        <label for="idStyle" class="form-label">Stile*</label>
                        <select wire:model="style" class="form-control @error('style') is-invalid @enderror">
                            <option value="">Scegli uno stile:</option>
                            @foreach ($styles as $style)
                                <option value="{{$style}}">{{$style}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Input categoria default --}}
                        <div class="mb-3">
                            <label for="idSubject" class="form-label">Soggetto principale*</label>
                            <textarea class="form-control @error('subject') is-invalid @enderror" id="idSubject" aria-describedby="" wire:model="subject" cols="30" rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="idAmbience" class="form-label">Ambientazione*</label>
                            <textarea class="form-control @error('ambience') is-invalid @enderror" id="idAmbience" aria-describedby="" wire:model="ambience" cols="30" rows="5"></textarea>
                        </div>
                    <div class="mb-3">
                        <label for="idOtherDetails" class="form-label">Altri dettagli</label>
                        <textarea class="form-control @error('otherDetails') is-invalid @enderror" id="idOtherDetails" aria-describedby="" wire:model="otherDetails" cols="30" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="idMainColor" class="form-label">Colore principale</label>
                        <input type="text" class="form-control @error('idMainColor') is-invalid @enderror" id="idMainColor" aria-describedby="" wire:model="mainColor">
                    </div>
                    {{-- <div class="d-flex justify-content-center">
                        @if($cover)
                            <img src="{{ Storage::url($cover) }}" alt="cover">
                        @endif
                    </div>
                    <x-loader /> --}}
                    <div>
                        @if($isGeneratingImage)
                            <x-loader />
                            <span wire:poll.visible="checkGeneratedImage"></span>
                        @endif
                        @if($cover)
                            <img src="{{ Storage::url($cover) }}" alt="cover" class="img-fluid img-glass-card d-block mx-auto">
                        @endif
                    </div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12 col-lg-10 pt-2 d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger btn-loader mt-2">{{ $cover ? 'Rigenera' : 'Genera' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{-- Salvataggio --}}
            <div class="{{ $step !== 3 ? 'd-none' : '' }}">
                {{-- Visualizzazione di errori in fase finale  --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row justify-content-center mb-3">
                    <div class="col-12 col-md-6">
                        <x-book-card
                            title="{{$title}}"
                            description="{{$description}}"
                            category="{{$categories->find($selectedCategory)?->name}}"
                            cover="{{$cover}}"
                            author="{{ Auth::user()->name}}"
                            price="{{$price}}"
                            url="#"
                        />
                    </div>
                </div>
                {{-- Switch di richiesta --}}
                <div class="col-12 mt-5 d-flex justify-content-center">
                    <div class="form-check form-switch">
                           <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" wire:model="askReview">
                            <label class="form-check-label" for="flexSwitchCheckDefault">{{$editMode ? 'Richiedi nuovamente recensione' : 'Richiedi recensione'}}</label>
                            <a href=""data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="ms-2 bi bi-info-circle text-dark"></i></a>
                    </div>
                </div>
                
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 d-flex {{ $step == 2 || $step == 3 ? 'justify-content-between' : 'justify-content-center' }}">
                        <button  type="button" class="btn btn-success {{ $step == 1 ? 'd-none' : '' }}" wire:click="prevStep">Indietro</button>
                        <button  type="button" wire:click="nextStep" class=" btn btn-success {{ $step == 3 ? 'd-none' : '' }}" {{$step == 2 && !$cover ? 'disabled' : ''}}>Avanti</button>
                        <div class="{{ $step == 3 ? '' : 'd-none' }}">
                            <form wire:submit="saveBook">
                                <button type="submit" class="btn btn-success {{ $step == 3 ? '' : 'd-none' }}">{{ $editMode ? 'Salva le modifiche' : 'Inserisci eBook'}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modale di info --}}
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                         <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="me-2 bi bi-info-circle text-white"></i>Info Revisione</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                        <div class="modal-body">
                             <p>Il team di AulaBook leggerà la tua pubblicazione. Riceverai al più presto pareri e consigli da parte di un esperto. Ti ricordiamo che la pubblicazione dell' ebook è indipendente dalla nostra recensione e lasciamo all'utente ogni libertà d'esepressione.</p>
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
              </div>
</div>
{{-- chiusure componente livewire --}}

