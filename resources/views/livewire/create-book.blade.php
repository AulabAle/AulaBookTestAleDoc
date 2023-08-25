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
       
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link {{ $step === 1 ? 'active' : '' }}" wire:click="changeStep(1)" >Upload</button>
            <button class="nav-link {{ $step === 2 ? 'active' : '' }}" wire:click="changeStep(2)" {{ $title ? '' : 'disabled' }}>Cover</button>
            <button class="nav-link {{ $step === 3 ? 'active' : '' }}" wire:click="changeStep(3)" {{ $promptToken ? '' : 'disabled' }}>Salvataggio</button>
        </div>
    </nav>
        <div class="tab-content my-3" id="nav-tabContent">
            {{-- Form inserimento dati libro --}}
            <div class="{{ $step !== 1 ? 'd-none' : '' }}">
                <form wire:submit.prevent="saveBook">         
                    {{-- input titolo --}}
                    <div class="mb-3">
                        <label id="titleId" class="form-label">Titolo*</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model.defer="title" for="titleId">
                        @error('title')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                        
                    </div>
                    {{-- input descrizione --}}
                    <div class="mb-3">
                        <label id="descriptionId" class="form-label">Descrizione del Libro*</label>
                        <textarea id="" cols="30" rows="10" class="form-control @error('description') is-invalid @enderror" wire:model.defer="description" for="descriptionId"></textarea>
                        @error('description')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- input categoria --}}
                    <div class="mb-3">
                        <label for="categoryId">Categoria</label>
                        <select wire:model.defer='selectedCategory' id="categoryId" class="form-control @error('selectedCategory') is-invalid @enderror">
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
                        <label id="price" class="form-label">Inserisci il prezzo €</label>
                        <input type="number" step="0.1" class="form-control @error('price') is-invalid @enderror" wire:model.defer="price" for="price">
                        @error('price')
                        <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- input pdf --}}
                        <div class="mb-3">
                            <label id="pdfId" class="form-label">Carica il tuo PDF*</label>
                            <input type="file" class="form-control @error('pdf') is-invalid @enderror" wire:model.defer="pdf"  accept=".pdf" for="pdfId">
                            @error('pdf')
                            <div class="p-0 small fst-italic text-danger">{{ $message }}</div>
                            @enderror
                        </div>
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
                <form wire:submit.prevent="generate">
                    <div class="mb-3">
                        <label for="idStyle" class="form-label">Stile*</label>
                        <select wire:model.defer="style" class="form-control @error('style') is-invalid @enderror">
                            <option value="">Scegli uno stile:</option>
                            @foreach ($styles as $style)
                                <option value="{{$style}}">{{$style}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Input categoria default --}}
                        <div class="mb-3">
                            <label for="idSubject" class="form-label">Soggetto principale*</label>
                            <textarea class="form-control @error('subject') is-invalid @enderror" id="idSubject" aria-describedby="" wire:model.defer="subject" cols="30" rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="idAmbience" class="form-label">Ambientazione*</label>
                            <textarea class="form-control @error('ambience') is-invalid @enderror" id="idAmbience" aria-describedby="" wire:model.defer="ambience" cols="30" rows="5"></textarea>
                        </div>
                    <div class="mb-3">
                        <label for="idOtherDetails" class="form-label">Altri dettagli</label>
                        <textarea class="form-control @error('otherDetails') is-invalid @enderror" id="idOtherDetails" aria-describedby="" wire:model.defer="otherDetails" cols="30" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="idMainColor" class="form-label">Colore principale</label>
                        <input type="text" class="form-control @error('idMainColor') is-invalid @enderror" id="idMainColor" aria-describedby="" wire:model.defer="mainColor">
                    </div>
                    <div class="d-flex justify-content-center">
                        @if($cover)
                            <img src="{{ Storage::url($cover) }}" alt="cover">
                        @endif
                    </div>
                    <x-loader />
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
                <div class="row justify-content-center mb-3">
                    <div class="col-12 col-md-6">
                        <x-book-card
                            title="{{$title}}"
                            description="{{$description}}"
                            category="{{$categories->find($selectedCategory)?->name}}"
                            cover="{{$cover}}"
                            author="{{ Auth::user()->name}}"
                            url="#"
                        />
                    </div>
                </div>
                
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 d-flex {{ $step == 2 || $step == 3 ? 'justify-content-between' : 'justify-content-center' }}">
                        <button  type="button" class="btn btn-success {{ $step == 1 ? 'd-none' : '' }}" wire:click="prevStep">Indietro</button>
                        <button  type="button" wire:click="nextStep" class=" btn btn-success {{ $step == 3 ? 'd-none' : '' }}" {{$step == 2 && !$cover ? 'disabled' : ''}}>Avanti</button>
                        <div class="{{ $step == 3 ? '' : 'd-none' }}">
                            <form wire:submit.prevent="saveBook">
                                <button type="submit" class="btn btn-success {{ $step == 3 ? '' : 'd-none' }}">Inserisci eBook</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</div>
