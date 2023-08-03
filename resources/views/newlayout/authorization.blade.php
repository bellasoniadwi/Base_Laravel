<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404</title>
 
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
 
        <!-- Styles -->
        <style>
        html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
        }
 
        .full-height {
        height: 100vh;
        }
 
        .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
        }
 
        .position-ref {
        position: relative;
        }
 
        .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
        }
 
        .content {
        text-align: center;
        }
 
        .title {
        font-size: 84px;
        }
 
        .m-b-md {
        margin-bottom: 30px;
        }

        .fcc-btn {
            background-color: #195e93;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-weight: bold;
        }
        </style>
    </head>
    <body>
        @include('newlayout.navbar')
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Oops, Kamu tidak memiliki akses pada sistem ini
                </div>
                <a class="fcc-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Keluar') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </body>
</html>