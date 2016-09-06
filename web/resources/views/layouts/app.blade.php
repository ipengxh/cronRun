<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CronRun</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/select2/select2-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/select2/select2.min.css">
    <link rel="stylesheet" href="/css/font-awesome/4.6.3/css/font-awesome.min.css">
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="/js/select2/select2.min.js"></script>
    <style>
        body {
            font-family: 'Source Code Pro', 'Monaco', 'Courier New', 'Courier';
            padding-top: 70px;
        }
    </style>
    <!-- Scripts -->
    <script>
        window.CSRF = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
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
                    CronRun
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                @if (Request::is('dashboard'))
                    <li class="active info">
                @else
                    <li>
                @endif
                        <a href="{{ url('/dashboard') }}">
                            <i class="fa fa-dashboard info"></i> Dashboard</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('task/*') or Request::is('tasks'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/tasks') }}">
                            <i class="fa fa-tasks"></i> Tasks</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('project/*') or Request::is('projects'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/projects') }}">
                            <i class="fa fa-laptop"></i> Projects</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('node/*') or Request::is('nodes'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/nodes') }}">
                            <i class="fa fa-cloud"></i> Nodes</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                @if (Request::is('settings'))
                    <li class="active">
                @else
                    <li>
                @endif
                        <a href="{{ url('/settings') }}">
                            <i class="fa fa-cog"></i> Settings</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        @if (Request::is('profile'))
                        <li class="dropdown active">
                        @else
                        <li class="dropdown">
                        @endif
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa fa-user"></i>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/profile') }}"><i class="fa fa-btn fa-user"></i> My profile</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @if (count($errors))
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (count(session('success')))
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <ul>
                        @foreach (session('success') as $message)
                        <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    @yield('content')
</body>
<script src="/js/hotkey.js"></script>
@yield('footer')
</html>
