<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            /* ! tailwindcss v3.2.4 | MIT License | https://tailwindcss.com */*,::after,::before
            .antialiased{
                display: flex;
                flex-direction: column;
                align-content: center;
                align-items: center;
                justify-content: center;
                height: 100%;width: 100%
            }

            form{
                padding: 20px;

            }
            input{
                margin: 10px;
            }
        </style>
    </head>
    <body class="antialiased">
        @if (\Session::has('error'))
            <div class="alert alert-error">
                <ul>
                    <li>{!! \Session::get('error') !!}</li>
                </ul>
            </div>
        @endif

        @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif


        <form method="post" action="{{url('login')}}">
            @csrf
                <label> Email </label>
                <input type="email" value="atif@gmail.com" placeholder="Enter email" name="email"/>
                <br/>
                <label> Password </label>
                <input type="password" value="testing" placeholder="Enter Password" name="password"/>
                <br/>
                <button type="submit" class="btn btn-primary">Submit</button>
         </form>

         <br/>
         <a href="{{url('register')}}">Signup</a>
    </body>
</html>
