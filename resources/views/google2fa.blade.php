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


        @if ($error)
            <p>{{ $error }}</p>
        @endif

        @if (\Session::has('id'))
        <div class="alert alert-id">
            <ul>
                <li>{!! \Session::get('id') !!}</li>
            </ul>
        </div>
        @endif



        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Set up 2FA') }}</div>
                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif



                        <div class="card-body">
                            @if(!true)
                                <div>2FA is activated!</div>
                                <span>To deactivate 2FA, click:</span>
                                {{-- <a href="{{ route('2fa.deactivate') }}">
                                    {{ __('deactivate 2FA') }}
                                </a> --}}
                            @else


                                <form method="POST" action="{{ route('google2fa') }}">
                                    @csrf

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Step 1</label>

                                        <div class="col-md-6">
                                            <span class="form-control-plaintext">Install a compatible app on your mobile device</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">Step 2</label>

                                        <div class="col-md-6">
                                            <span class="form-control-plaintext">Scan the following QR code</span>
                                            <div>
                                                {!! $qrCode !!}
                                                <div>
                                                    <small>Alternatively, you can type the secret key.</small>
                                                    <pre class="bg-light p-1">{{ $secret }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="otp" class="col-md-4 col-form-label text-md-right">Step 3</label>

                                        <div class="col-md-6">
                                            <span class="form-control-plaintext">Type the 2FA token below for verification</span>

                                            <input id="otp" type="text"
                                                   class="form-control @error('one_time_password') is-invalid @enderror"
                                                   name="one_time_password" required>

                                            @error('one_time_password')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Submit') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <a href="{{url('/')}}">Login</a>
    </body>
</html>
