<x-layouts.layout>

    @if(session()->has('message'))
        <div class="d-flex justify-content-center my-2 alert alert-success">
            {{session('message')}}
        </div>
    @endif

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <h1 class="text-center">
                    Titolo del libro: {{$book->title}}
                </h1>
            </div>
        </div>
    </div>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="d-flex justify-content-center col-12 col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="{{$book->cover ? Storage::url($book->cover) : '/img/default.png'}}" class="card-img-top" alt="Picsum photo">
                    <div class="card-body">
                        <h5 class="card-title">{{$book->title}}</h5>
                        <p class="card-text">{{$book->description}}</p>
                        <p class="card-text">Categoria: {{$book->category->name}}</p>
                        <a href="{{route('book.category', ['category'=>$book->category])}}" style="font-size: 14px;">Visualizza altri libri per questa categoria</a>

                        @if(!($book->isBookPurchased() || $book->isBookAuthor()))
                            <div class="mt-3">
                                <form method="POST" action="{{route('checkout', compact('book'))}}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 mb-2">
                                @if($book->price == 0)
                                    Aggingi alla tua libreria
                                @else
                                    Acquista {{$book->price}} €
                                @endif
                                </button>
                                </form>
                            </div>
                        @else
                            <h6 class="mt-3 mb-3">Hai giá acquistato questo libro</h6>
                        @endif

                        @auth
                            @if( $book->isBookPurchased() || $book->isBookAuthor())
                                <a href="{{route('book.download', compact('book'))}}" class="btn btn-primary w-100 mb-2">
                                    Scarica
                                </a>
                                <a href="{{route('book.viewPdf', compact('book'))}}" class="btn btn-primary w-100 mb-2">
                                    Visualizza
                                </a>
                            @endif
                        @endauth

                        {{-- <a href="{{route('book.download', compact('book'))}}" class="btn btn-primary w-100 mb-2">Scarica</a>
                        <a href="{{route('book.viewPdf', compact('book'))}}" class="btn btn-primary w-100 mb-2">Visualizza</a> --}}
                        <a href="{{route('book.index')}}" class="btn btn-primary w-100">Torna indietro</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-layouts.layout>