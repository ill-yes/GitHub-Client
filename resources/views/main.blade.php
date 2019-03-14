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
            <li class="nav-item {{ Request::url() == url('/user') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="{{ route('user') }}">User</a>
            </li>
            <li class="nav-item {{ Request::url() == url('/repository') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="{{ route('repository') }}">Repository</a>
            </li>
            <li class="nav-item {{ Request::url() == url('/branches') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="{{ route('branches') }}">Branches</a>
            </li>
            <li class="nav-item {{ Request::url() == url('/pr-location') ? 'active font-weight-normal' : '' }}">
                <a class="nav-link" href="{{ route('pr-location') }}">PR Location</a>
            </li>
        </ul>

        @if (!session()->exists('username'))
            <form class="form-inline" method="POST" action="{{ route('login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group no-gutter">
                    <input type="text" class="form-control" placeholder="Username" id="usernameField" name="username" min="1" max="30">
                    <span id="tokenInfoText" style="display: none;">
                        <a href="https://github.com/settings/tokens" data-toggle="tooltip" data-placement="bottom" title="Link to GitHub-Token page">Token</a>
                    </span>
                </div>

                <div class="form-group no-gutter">
                    <input type="password" class="form-control" placeholder="Password" id="passwordField" name="password" min="1" max="255">
                </div>

                <div class="form-check no-gutter">
                    <input type="checkbox" class="form-check-input" id="tokenCheckbox" name="tokenCheck" onclick="onClickEvent();">
                    <label class="form-check-label" for="tokenCheckbox">
                        <a class="fas fa-key"></a>
                    </label>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        @endif
        @if (session()->exists('username'))
            <ul class="navbar-nav">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">


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
