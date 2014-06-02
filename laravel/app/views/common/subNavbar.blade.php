<div class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="{{ URL::to($zone) }}">{{ $navbar[$zone] }}</a>
        <ul class="nav">
            <li class="divider-vertical"></li>
            @foreach ($subNavbar as $uri => $item)
                <li @if(Request::is($zone ."/" . $uri . "*") || (Request::is($zone) && $uri=="/")) class="active" @endif><a href="{{ URL::to($zone ."/" . $uri) }}">{{ $item }}</a></li>
            @endforeach
        </ul>
    </div>
</div>