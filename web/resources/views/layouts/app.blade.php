<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="/font-awesome/4.6.3/css/font-awesome.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="/bootstrap/3.3.7/css/bootstrap.min.css">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Monaco', 'Courier New', 'Courier';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    cronRun
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                @if (Request::is('dashboard'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('server/*'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/server/index') }}">
                            <i class="fa fa-cloud"></i>
                            Servers
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('project/*'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/project/index') }}">
                            <i class="fa fa-laptop"></i>
                            Projects
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('task/*'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/task/index') }}">
                            <i class="fa fa-tasks"></i>
                            Tasks
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('settings'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/settings') }}">
                            <i class="fa fa-cog"></i>
                            Settings
                        </a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa fa-user"></i>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i>My profile</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="/jquery/3.1.0/jquery.min.js"></script>
    <script src="/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
