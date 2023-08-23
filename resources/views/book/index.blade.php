<x-layouts.layout>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <h1 class="text-center">
                    Tutti i libri
                </h1>
            </div>
        </div>
    </div>

    <div class="col-12 mt-5">
        <div class="container my-5">
            <div class="row">
                @forelse ($books as $book)
                    <div class="col-12 col-md-6 col-lg-4 my-3">
                        <x-book-card
                            title="{{$book->title}}"
                            description="{{$book->description}}"
                            author="{{$book->user->name}}"
                            cover="{{$book->cover}}"
                            url="{{route('book.show', compact('book'))}}"
                        />
                    </div>
                @empty
                    <div>
                        <h3 class="text-center">
                            Non é stato pubblicato ancora nessun libro
                        </h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
  <div class="d-flex justify-content-center">
        <div>
            {{$books->links()}}
        </div>
    </div> 
</x-layouts.layout>