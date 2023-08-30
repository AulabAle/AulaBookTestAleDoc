<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{route('welcome')}}">AulaBook</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{route('welcome')}}">Home</a>
          </li>
          <li class="nav-item">
             {{-- <a class="nav-link" href="{{route('book.index')}}">Tutti i libri</a> --}}
             <a class="nav-link" href="{{ route('book.indexFilters') }}">Sfoglia tutti i libri</a>
          </li>
          @guest
        <li class="nav-item">
          <a class="nav-link" href="{{route('register')}}">Registrati</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('login')}}">Login</a>
        </li>
        @endguest
        @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Benventuto {{Auth::user()->name}}
          </a>
          <ul class="dropdown-menu">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
            {{-- Zona revisore --}}
            @if (Auth::user()->isRevisor() || Auth::user()->isAdmin())
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('revisor.index') }}">Zona Revisore</a>
                  </li>
            @endif
            {{-- Profilo utente --}}
            <li class="nav-item">
              <a class="nav-link" href="{{ route('user.profile') }}">I tuoi Libri</a>
            </li>
          </ul>
        </li>
        @endauth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categorie
          </a>
          <ul class="dropdown-menu">
              @foreach ($categories as $category)
                <li class="nav-item">
                  <li class="nav-item">
                    <form action="{{route("book.indexFilters")}}" method="GET">
                      <input type="hidden" name="categoryChecked[0]" value="{{$category->id}}">
                      <button type="submit" class="nav-link" >{{($category->name)}}</button>
                    </form>
                  </li>
                </li>
              @endforeach
          </ul>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
