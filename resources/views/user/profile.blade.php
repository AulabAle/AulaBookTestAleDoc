<x-layouts.layout>
    <div class="container my-5">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="display-2 text-center font-bold">
                    Le mie Pubblicazioni : {{Auth::user()->name}} 
                </h2>
            </div>
        </div>
    </div>
    @if(count($books) > 0)
        <div class="container-fluid my-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 d-none d-sm-block">
                    {{-- tabella --}}
                    <table class="table table-bordered border-dark shadow ">
                        <thead>
                        <tr>
                            <th scope="col">Titolo</th>
                            <th scope="col">Descrizione</th>
                            <th scope="col">Cover</th>
                            <th scope="col">Actions</th>
                            <th scope="col">Publish</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Price</th>
                            <th scope="col">Revisor review</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $book)
                                <tr>
                                    <td>{{$book->title}}</td>
                                    <td>{{$book->getDescriptionSubstring()}}</td>
                                    <td class="d-flex justify-content-center">
                                        @if ($book->cover)
                                            <img src="{{Storage::url($book->cover)}}" class="rounded-circle " width="20%" height="20%" alt="">
                                        @else
                                            <img src="/img/default.jpg" class="rounded-circle " width="20%" height="20%" alt="">
                                        @endif 
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{route('book.viewPdf', compact('book'))}}" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            @if ($book->is_published)
                                                <form action="{{ route('user.unpublish', compact('book')) }}" method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <button type="submit" class="btn btn-danger mx-3 shadow">Nascondi</button>
                                                </form>
                                            @else
                                                <form action="{{ route('user.publish', compact('book')) }}" method="POST">
                                                    @csrf
                                                    @method('patch')
                                                    <button type="submit" class="btn btn-success mx-3 shadow">Pubblica</button>
                                                </form> 
                                            @endif
                                        </div>    
                                    </td>
                                    <td>
                                        @if($book->category)
                                            {{$book->category->name}}
                                        @else
                                            Nessuna categoria selezionata
                                        @endif
                                    </td>
                                    <td>
                                        @if($book->price > 0)
                                            {{$book->price}} €
                                        @else
                                            Free
                                        @endif
                                    </td>
                                    <td>
                                        <div class="container">
                                            <div class="row justify-content-center">
                                                <div class="col-12 col-md-6 d-flex justify-content-center">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal_{{$book->id}}">
                                                        <i class="bi bi-chat-right-quote"></i>
                                                    </button>
                                                </div>
                                            </div>
                                                                
                                    </td>
                                    <!-- Modale recensioni -->
                                    <div class="modal fade" id="exampleModal_{{$book->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Recensione Revisore</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @forelse ($book->reviews as $review)
                                                <p class="mb-1">Revisore {{date('d-m-Y H:i', strtotime($review->created_at))}} : </p>
                                                <p>{{$review->content}}</p>
                                                @empty
                                                    <h2>Non ci sono recensioni</h2>
                                                @endforelse
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach 
                        
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-8 d-block d-sm-none">
                    {{-- mobile --}}
                    @foreach ($books as $book)
                    <div class="col-12 d-flex justify-content-center my-3" >
                        <div class="card bg-glass d-flex justify-content-center" style="width: 18rem;">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bg-glass d-flex justify-content-between">
                                        <p class="mt-2">Azioni :</p>
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu bg-custom-dark p-2">
                                                <li>
                                                    <a class="dropdown-item text-decoration-none text-white-custom" href="{{route('book.show', compact('book'))}}">
                                                        <i class="bi bi-eye px-2"></i> Dettaglio
                                                    </a>
                                                </li>   
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    @if ($book->is_published)
                                                        <form action="{{ route('user.unpublish', compact('book')) }}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button type="submit" class="btn btn-outline-danger shadow d-block mx-auto mt-1"><i class="bi bi-eye-slash pe-1"></i> Nascondi</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('user.publish', compact('book')) }}" method="POST">
                                                            @csrf
                                                            @method('patch')
                                                            <button type="submit" class="btn btn-outline-success shadow d-block mx-auto"><i class="bi bi-eye pe-1"></i> Pubblica</button>
                                                        </form> 
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="list-group-item bg-glass">
                                        <h5 class="card-title">Titolo : {{$book->title}}</h5>
                                    </li>
                                    <li class="list-group-item bg-glass">
                                        <p class="card-text">Prezzo : 
                                                @if($book->price > 0)
                                                    {{$book->price}} €
                                                @else
                                                    Free
                                                @endif
                                        </p>   
                                    </li>
                                    <li class="list-group-item bg-glass">                                  
                                        <p>Categoria :
                                            @if($book->category)
                                                {{$book->category->name}}
                                            @else
                                                Categoria non presente
                                            @endif
                                        </p>
                                    </li>
                                    <li class="list-group-item bg-glass d-flex justify-content-between">                                   
                                        <p class="mt-3">
                                            Recensioni :
                                        </p>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal_{{$book->id}}">
                                            Leggi <i class="bi bi-chat-right-quote text-white-custom ms-2"></i>
                                        </button>
                                    </li>
                                    <li class="list-group-item bg-glass  d-flex justify-content-between">    
                                        <p class="mt-2"> Stato :
                                            @if ($book->is_published)
                                                <p class="mb-0 mt-2 text-success"><i class="bi bi-check-circle me-2"></i> Pubblicato</p>
                                            @else
                                                <p class="mb-0 mt-2 text-danger"><i class="bi bi-x-circle me-2"></i> Nascosto</p>
                                            @endif
                                        </p>
                                    </li>                        
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach  
                </div>
            </div>
        </div>
    @endif

    <div class="container my-5">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="display-2 text-center font-bold">
                    Libri acquistati
                </h2>
            </div>
        </div>
    </div>
    
    @if(count($purchasedBooks) > 0)
        <div class="container-fluid my-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 d-none d-sm-block">
                    {{-- tabella --}}
                    <table class="table table-bordered border-dark shadow ">
                        <thead>
                        <tr>
                            <th scope="col">Titolo</th>
                            <th scope="col">Descrizione</th>
                            <th scope="col">Cover</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Price</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchasedBooks as $pBook)
                                <tr>
                                    <td>{{$pBook->book->title}}</td>
                                    <td>{{$pBook->getDescriptionSubstring()}}</td>
                                    <td class="d-flex justify-content-center">
                                        @if ($pBook->book->cover)
                                            <img src="{{Storage::url($pBook->book->cover)}}" class="rounded-circle " width="20%" height="20%" alt="">
                                        @else
                                            <img src="{{$pBook->book->cover}}" class="rounded-circle " width="20%" height="20%" alt="">
                                        @endif 
                                    </td>
                                    
                                    <td>
                                        @if($pBook->book->category)
                                            {{$pBook->book->category->name}}
                                        @else
                                            Nessuna categoria selezionata
                                        @endif
                                    </td>
                                    <td>
                                        @if($pBook->book->price > 0)
                                            {{$pBook->book->price}} €
                                        @else
                                            Free
                                        @endif
                                    </td>
                                    <td class="ps-3">
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu bg-custom-dark p-2">
                                                <li>
                                                    <a class="dropdown-item text-decoration-none" href="{{route('book.download', ['book'=> $pBook->book])}}">
                                                        <i class="bi bi-box-arrow-down px-2"></i>
                                                        Scarica
                                                    </a>
                                                </li>   
                                                <li>
                                                    <a class="dropdown-item text-decoration-none" href="{{route('book.show', ['book'=> $pBook->book])}}">
                                                        <i class="bi bi-eye px-2"></i>
                                                        Visualizza
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach 
                        
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-8 d-block d-sm-none">
                    {{-- mobile --}}
                    @foreach ($purchasedBooks as $pBook)
                    <div class="col-12 d-flex justify-content-center my-3 ">
                        <div class="card bg-glass d-flex justify-content-center" style="width: 18rem;">
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bg-glass d-flex justify-content-between">
                                        <p class="mt-2">Azioni :</p>
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu bg-custom-dark p-2">
                                                <li>
                                                    <a class="dropdown-item text-decoration-none text-white-custom" href="{{route('book.download', ['book'=> $pBook->book])}}">
                                                        <i class="bi bi-box-arrow-down px-2"></i>
                                                        Scarica
                                                    </a>
                                                </li>   
                                                <li>
                                                    <a class="dropdown-item text-decoration-none text-white-custom" href="{{route('book.show', ['book'=> $pBook->book])}}">
                                                        <i class="bi bi-eye px-2"></i>
                                                        Dettaglio
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="list-group-item bg-glass">
                                        <h5 class="card-title">Titolo : {{$pBook->book->title}}</h5>
                                    </li>
                                    <li class="list-group-item bg-glass">
                                        <p class="card-title">Descrizione : {{$pBook->getDescriptionSubstring()}}</p>
                                    </li>
                                    <li class="list-group-item bg-glass">
                                        <p class="card-text">Prezzo : 
                                            @if($pBook->book->price > 0)
                                                {{$pBook->book->price}} €
                                            @else
                                                Free
                                            @endif
                                        </p>   
                                    </li>
                                    <li class="list-group-item bg-glass">                                  
                                        <p>Categoria :
                                            @if($pBook->book->category)
                                            {{$pBook->book->category->name}}
                                        @else
                                            Nessuna categoria selezionata
                                        @endif
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach  
                </div>
            </div>
        </div>
    @endif
</x-layouts.layout>