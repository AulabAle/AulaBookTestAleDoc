<div class="card text-white card-has-bg position-relative" style="background-image:url({{$cover ? Storage::url($cover) : '/img/default.png'}});">
    <a class="text-white text-decoration-none" href="{{ $url }}">
        <div class="card-img-overlay d-flex flex-column">
            <div class="card-body">
                <h4 class="card-title mt-0 ">{!!$title!!}</h4>
                <small>{!!$description!!}</small>
                <h6>{{$category}}</h6>
            </div>
            <div class="card-footer">
                <div class="media">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="my-0 text-white">{{ $author }}</h6>
                        <div class="mt-2">
                            @if($price == 0)
                                <p class="fs-3 fw-bold">Free</p>
                            @else
                                <p class="fs-3 fw-bold">€ {{$price}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>