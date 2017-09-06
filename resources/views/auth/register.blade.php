@extends('layouts.app')

@section('title') Registro @endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Registro</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Correo electrónico</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmar Contraseña</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        {!! Recaptcha::render() !!}

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-fw fa-sign-in" aria-hidden="true"></i> Registrarse
                                </button>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                o iniciar sesión con las redes sociales
                            </div>

                            <div class="col-md-6 col-md-offset-4" style="margin-top: 15px;">
                                <a class="btn btn-md btn-primary" style="background-color: #3b5998; width: 100%;" href="{{ url('/redirect/facebook') }}">
                                    <i class="fa fa-fw fa-facebook-square fa-2x"></i>  Iniciar sesión con Facebook
                                </a>
                            </div>

                            <div class="col-md-6 col-md-offset-4" style="margin-top: 15px;">
                                <a class="btn btn-md btn-primary" style="background-color: #00aced; width: 100%;" href="{{ url('/redirect/twitter') }}">
                                    <i class="fa fa-fw fa-twitter-square fa-2x"></i>  Iniciar sesión con Twitter
                                </a>
                            </div>

                            <div class="col-md-6 col-md-offset-4" style="margin-top: 15px;">
                                <a class="btn btn-md btn-primary" style="background-color: #CC3335; width: 100%;" href="{{ url('/redirect/google') }}">
                                    <i class="fa fa-fw fa-google-plus-square fa-2x"></i>  Iniciar sesión con Google+
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
