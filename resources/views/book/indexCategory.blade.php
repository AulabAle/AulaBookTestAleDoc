<x-layouts.layout>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <h1 class="text-center">
                    Tutti i libri per la categoria {{$category->name}}
                </h1>
            </div>
        </div>
    </div>
    
    <div class="container my-5">
        <div class="row row justify-content-center">
            {{-- @forelse ($category->books as $book) --}}
            @forelse ($books as $book)
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <x-book-card
                        title="{{$book->title}}"
                        description="{{$book->getDescriptionSubstring()}}"
                        cover="{{$book->cover}}"
                        author="{{$book->user->name}}"
                        category="{{$book->category->name}}"
                        url="{{route('book.show', compact('book'))}}"
                        price="{{ $book->price }}"
                    />
                </div>
            @empty
                <h2 class="text-center">
                    Non é stato pubblicato ancora nessun libro
                </h2>
                <div class="col-12 col-md-8 col-lg-4 d-flex justify-content-center">
                    <a class="btn btn-danger" href="{{route('book.create')}}">Pubblica un libro</a>
                </div>
            @endforelse
        </div>
    </div>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 d-flex justify-content-center">
                <a href="{{ url()->previous() }}" class="btn btn-primary mt-5">Torna Indietro</a>
            </div>
        </div>
    </div>
</x-layouts.layout>