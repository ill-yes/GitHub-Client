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
        .no-gutter {
            padding-right: 5px;
            padding-left: 5px;
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
            <li class="nav-item {{ Request::url() == url('/') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="/">Home</a>
            </li>
            @auth
            <li class="nav-item {{ Request::url() == url('/branches') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="{{ route('branches') }}">Branches</a>
            </li>
            <li class="nav-item {{ Request::url() == url('/pr-location') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="{{ route('pr-location') }}">PR Location</a>
            </li>
            @endauth
        </ul>

        <ul class="navbar-nav ml-auto">
        @guest
            <li class="nav-item">
                <a id="github-button" class="btn btn-block btn-social btn-github" href="{{ route('login') }}" style="color:white">
                    <i class="fa fa-github"></i>Sign in with Github
                </a>
            </li>
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ auth()->user()->username }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        @endguest
        </ul>
    </div>
</nav>

<div class="container">

    @yield('content')

</div><!-- /.container -->

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('js/app.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.12.0/bootstrap-social.min.css">


@section('js')
    <script>
        function onClickEvent()
        {
            var checkbox = document.getElementById("tokenCheckbox");
            var usernameField = document.getElementById("usernameField");
            var tokenInfoText = document.getElementById("tokenInfoText");
            var passwordField = document.getElementById("passwordField");

            if (checkbox.checked === true)
            {
                usernameField.style.display= 'none';
                usernameField.value = '';
                tokenInfoText.style.display= 'inline';
                passwordField.placeholder = 'Key';
                passwordField.value = '';
            }
            else if (checkbox.checked === false)
            {
                usernameField.style.display= 'inline';
                tokenInfoText.style.display= 'none';
                passwordField.placeholder = 'Password';
            }
        }
    </script>
@endsection

    @yield('js')

</body>
</html>
