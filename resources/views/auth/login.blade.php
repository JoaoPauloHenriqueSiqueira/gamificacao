{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','Acessar')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/login.css')}}">
@endsection


{{-- page content --}}
@section('content')
<style>
    .modal {
        max-height: 50%;
    }

    .modal .modal-content {
        padding: 14px;
    }
</style>
<div id="login-page" class="row">
    <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="row">
                <div class="input-field col s12">
                    <h5 class="ml-4">{{ __('Entrar') }}</h5>
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="material-icons prefix pt-2">person_outline</i>
                    <input id="email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <label for="email" class="center-align">{{ __('Email') }}</label>
                    @error('email')
                    <small class="red-text ml-10" role="alert">
                        {{ $message }}
                    </small>
                    @enderror
                </div>
            </div>
            <div class="row margin">
                <div class="input-field col s12">
                    <i class="material-icons prefix pt-2">lock_outline</i>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    <label for="password">{{ __('Senha') }}</label>
                    @error('password')
                    <small class="red-text ml-10" role="alert">
                        {{ $message }}
                    </small>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12 ml-2 mt-1">
                    <p>
                        <label>
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Lembrar de mim</span>
                        </label>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Login
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s4 m4 l4">
                    <p class="margin medium-small"><a onclick="openModal()">Criar uma conta</a></p>
                </div>
                <div class="input-field col s4 m4 l4">
                    <p class="margin medium-small"><a href="{{ route('register') }}">Registrar minha empresa</a></p>
                </div>
                <div class="input-field col s4 m4 l4">
                    <p class="margin right-align medium-small">
                        <a href="{{ ('password.request') }}">Esqueceu a senha?</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>


<div id="modalLoad" class="modal modal-fixed-footer">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h5 class="center  white-text row">Entre em contato com o administrador para ser incluído no sistema</h5><br>
        <br>
        <div class="row center">
            <img class="media-responsive center" src="https://media1.tenor.com/images/f3c7e8668794c95d724373959b8b71f5/tenor.gif?itemid=6206352">
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Ok</a>
    </div>
</div>
@endsection

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $("#modalLoad").modal();
    })

    function openModal() {
        $('#modalLoad').modal('open');
    }
</script>