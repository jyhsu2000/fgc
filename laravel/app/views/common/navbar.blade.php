<div class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="{{ URL::to('/') }}">{{ $sitename }}</a>
        <ul class="nav">
            <li class="divider-vertical"></li>
            @if(member::check())
                <li @if(strstr(Route::current()->getUri(),'profile') && $email=='' ) class="active" @endif>
                    <a href="{{ URL::to('profile') }}" title="個人檔案">
                        @if(member::getType()=="local")
                        <i class="fa fa-user fa-lg"></i>
                        @elseif(member::getType()=="facebook")
                        <i class="fa fa-facebook-square fa-lg"></i>
                        @elseif(member::getType()=="google")
                        <i class="fa fa-google-plus-square fa-lg"></i>
                        @endif
                        {{ member::getName() }}
                    </a>
                </li>
                <li class="divider-vertical"></li>
                @if(member::getGroup() == 'unverified')
                    <li><a href="{{ URL::to('resendVerifyCode') }}" title="信箱未驗證"><span style="color:red"><i class="fa fa-exclamation-triangle fa-lg"></i></span></a></li>
                    <li class="divider-vertical"></li>
                @endif
            @endif 
            @foreach ($navbar as $uri => $item)
                @if($uri=="---")
                    <li class="divider-vertical"></li>
                @else
                    <li @if((strstr(Route::current()->getUri(),$uri) && $uri!="/") || Route::current()->getUri()==$uri) class="active" @endif><a href="{{ URL::to($uri) }}">{{ $item }}</a></li>
                @endif
            @endforeach
        </ul>
    </div>
</div>