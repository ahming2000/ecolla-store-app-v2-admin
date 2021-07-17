<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('style')

</head>

<body style="min-width: 100%;">

<div id="app">
    <nav class="fixed-top navbar navbar-expand-md navbar-light bg-white shadow" style="z-index: 10;">
        <div class="container">

            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('img/icon/ecolla_icon.png') }}"
                     width="30" height="30"
                     class="d-inline-block align-top"
                     alt="" loading="lazy">
                管理员后台
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">

                <ul class="navbar-nav ms-auto">

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">登录</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/changing-log') }}">应用更新日志</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">主页</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/item') }}">商品</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/order') }}">订单</a></li>
                        @if(auth()->user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ url('/account') }}">员工账户管理</a></li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                @if(auth()->user()->hasAccess('setting_view'))
                                    @if(auth()->user()->hasAccess('setting_item') || auth()->user()->hasAccess('setting_order'))
                                        <a class="dropdown-item" href="{{ url('/setting/website') }}">网站属性设置</a>
                                    @endif
                                    @if(auth()->user()->hasAccess('setting_account'))
                                        <a class="dropdown-item" href="{{ url('/setting/account') }}">个人账号设置</a>
                                    @endif
                                @endif
                                <a class="dropdown-item" href="{{ url('/changing-log') }}">应用更新日志</a>
                                <a class="dropdown-item"
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    登出
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4" style="margin-top: 100px;">
        @yield('content')
    </main>

</div>

<!-- Scripts -->
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

@yield('script')

</body>
</html>

