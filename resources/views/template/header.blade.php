<header class="header-top" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>

                <div class="header-search">
                    <div class="input-group">

                        <span class="input-group-addon search-close">
                            <i class="ik ik-x"></i>
                        </span>
                        <input type="text" class="form-control">
                        <span class="input-group-addon search-btn"><i class="ik ik-search"></i></span>
                    </div>
                </div>

                <button type="button" id="navbar-fullscreen" class="nav-link"><i
                        class="ik ik-maximize"></i></button>
            </div>
            <div class="top-menu d-flex align-items-center">
                {{-- Notification --}}
                <div class="dropdown">
                    {{-- <a class="nav-link dropdown-toggle" href="#" id="notiDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-bell"></i><span class="badge bg-danger">{{ App\Models\Notifikasi::where('to_user_id', auth()->user()->id)->where('status', 'belum')->count() }}</span></a> --}}
                    <a class="nav-link dropdown-toggle" href="#" id="notiDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-bell"></i><span class="badge bg-danger">#</span></a>
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notiDropdown">
                        <h4 class="header">{{ __('Notifications')}}</h4>
                        <div class="notifications-wrap" id="notifikasi">
                            {{-- @foreach (App\Models\Notifikasi::where('to_user_id', auth()->user()->id)->where('status', 'belum')->get() as $item) --}}
                            {{-- <form class="media" action="{{ route('aduan.notification', $item->id) }}" method="POST">
                                @csrf
                                @method('put') --}}
                                {{-- <a href="{{ route('aduan.notification', $item->hasAduan->id) }}" class="media">
                                    <span class="d-flex">
                                        <i class="ik ik-bell"></i> 
                                    </span>
                                    <span class="media-body">
                                        <span class="heading-font-family media-heading">{{ $item->title }}</span> 
                                        <span class="media-content">{{ $item->body }}</span>
                                    </span>
                                </a>
                            {{-- </form> --}}
                            {{-- @endforeach --}}
                        </div>
                        <div class="footer"><a href="javascript:void(0);">{{ __('See all activity')}}</a></div>
                    </div>
                </div>
                <div class="dropdown">
                    <span>{{ ucfirst(Auth::user()->name) }}</span>
                </div>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><img class="avatar"
                            src="{{ asset('img/user.jpg') }}" alt=""></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('user.ubah') }}"><i
                                class="ik ik-user dropdown-icon"></i>
                            {{ __('Profile') }}</a>
                        {{-- <a class="dropdown-item" href="#"><i class="ik ik-navigation dropdown-icon"></i>
                            {{ __('Message') }}</a> --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ik ik-power dropdown-icon"></i>
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
