<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GitClient</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">

        <a class="navbar-brand" href="/">
            <i class="fab fa-github"></i>
            GitHub Client
        </a>

        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ Request::url() == url('/') ? 'active' : '' }}">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item {{ Request::url() == url('/user') ? 'active' : '' }}">
                <a class="nav-link" href="/user">User</a>
            </li>
            <li class="nav-item {{ Request::url() == url('/repository') ? 'active' : '' }}">
                <a class="nav-link" href="/repository">Repository</a>
            </li>
        </ul>

        @if (!session()->exists('username'))
            <form class="form-inline" method="POST" action="{{ route('login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" name="username" min="1" max="30">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" min="1"
                           max="255">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        @endif
        @if (session()->exists('username'))

            <ul class="navbar-nav   ">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ session()->get('username') }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                </div>
            </li>
            </ul>

        @endif
    </div>
</nav>

<div class="container">

    @yield('content')

</div><!-- /.container -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
