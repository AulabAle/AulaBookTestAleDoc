<x-layouts.layout>

    @if(session()->has('errorMessage'))
        <div class="d-flex justify-content-center my-2 alert alert-danger">
            {{session('errorMessage')}}
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <header class="container my-5 py-5">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-lg-6 col-xl-5 order-2 order-lg-1 text-center text-lg-start">
                    <h1 class="display-2 font-bold">AulaBook</h1>
                    <p class="my-5">
                        {{__('ui.homeDescription')}}
                    </p>
                    <a href="{{route('book.create')}}" class="btn btn-primary">Pubblica un Libro</a>
                </div>
                <div class="col-12 col-lg-6 col-xl-5 text-center order-1 order-lg-2">
                    <img src="{{asset('img/header-image.png')}}" alt="aulabit" class="img-fluid">
                </div>
            </div>
    </header>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 my-5 d-flex flex-column align-items-center">
                <h2 class="display-3 text-center font-bold">
                    Gli Ultimi libri
                </h2>
                <a href="{{ route('book.indexFilters') }}" class="btn btn-primary ms-3">{{__('ui.allBooks')}}</a>
            </div>
            @forelse ($books as $book)
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <x-book-card
                        title="{{$book->title}}"
                        description="{{$book->getDescriptionSubstring()}}"
                        author="{{$book->user->name}}"
                        category="{{$book->category->name}}"
                        cover="{{$book->cover}}"
                        price="{{$book->price}}"
                        url="{{route('book.show', compact('book'))}}"
                    />
                </div>
            @empty
                <h2 class="text-center">
                    Non ci sono libri pubblicati
                </h2>
            @endforelse
        </div>
    </div>
</x-layouts.layout>
