<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Hide Fedora</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    {!! HTML::style(asset('css/common.css')) !!}
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">YouTube Cleaner</a>
                </div>

                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        @if (basename($_SERVER['REQUEST_URI']) == 'home' || basename($_SERVER['REQUEST_URI']) == 'hidefedora' || basename($_SERVER['REQUEST_URI']) == 'public')
                            <li class="active"><a href="{{getenv('BASE_URL')}}/home">Home</a></li>
                        @else
                            <li><a href="{{getenv('BASE_URL')}}/home">Home</a></li>
                        @endif

                        @if (basename($_SERVER['REQUEST_URI']) == 'reports')
                            <li class="active"><a href="{{getenv('BASE_URL')}}/reports">Reports</a></li>
                        @else
                            <li><a href="{{getenv('BASE_URL')}}/reports">Reports</a></li>
                        @endif

                        @if (basename($_SERVER['REQUEST_URI']) == 'history')
                            <li class="active"><a href="{{getenv('BASE_URL')}}/history">History</a></li>
                        @else
                            <li><a href="{{getenv('BASE_URL')}}/history">History</a></li>
                        @endif

                        @if (basename($_SERVER['REQUEST_URI']) == 'about')
                            <li class="active"><a href="{{getenv('BASE_URL')}}/about">About</a></li>
                        @else
                            <li><a href="{{getenv('BASE_URL')}}/about">About</a></li>
                        @endif

                        @if (basename($_SERVER['REQUEST_URI']) == 'contact')
                            <li class="active"><a href="{{getenv('BASE_URL')}}/contact">Contact</a></li>
                        @else
                            <li><a href="{{getenv('BASE_URL')}}/contact">Contact</a></li>
                        @endif
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                            @if (basename($_SERVER['REQUEST_URI']) == 'login')
                                <li class="active"><a href="{{getenv('BASE_URL')}}/auth/login">Login</a></li>
                            @else
                                <li><a href="{{getenv('BASE_URL')}}/auth/login">Login</a></li>
                            @endif

                            @if (basename($_SERVER['REQUEST_URI']) == 'register')
                                <li class="active"><a href="{{getenv('BASE_URL')}}/auth/register">Register</a></li>
                            @else
                                <li><a href="{{getenv('BASE_URL')}}/auth/register">Register</a></li>
                            @endif
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{Auth::user()->name}} <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Profile</a></li>

                                    <li class="divider"></li>
                                    @if (Auth::user()->user_level > 1)
                                        <li><a href="#">Admin</a></href></li>
                                    @endif
                                    <li><a href="#">Settings</a></li>
                                    <li><a href="{{getenv('BASE_URL')}}/auth/logout">Logout</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-fluid -->
        </nav>
    </div>


    <div class="container">
    	@yield('content')
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>