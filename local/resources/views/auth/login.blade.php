@extends('layouts.front.app')

@section('content')
    <hr>
    <!-- Main content -->
    <section class="container content">
    <div class="col-md-8 col-md-offset-2" style="background-color:#fff;padding:5%;margin-bottom:20px">
        <div class="col-md-12">@include('layouts.errors-and-messages')</div>
        <div class="col-md-12">
            <h2>Connexion</h2>
            <hr>
            <form action="{{ route('login') }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" value="" class="form-control" placeholder="xxxxx">
                </div>
                <div class="row" style="margin-top:5%">
                    <button class="btn btn-primary btn-block" type="submit">Connexion</button>
                </div>
            </form>
            <div class="row">
                <hr>
                <a href="{{route('password.request')}}">J'ai oublié mon mot de passe</a><br>
                <a href="{{route('register')}}" class="text-center">Vous n'avez pas de compte ? Inscrivez vous.</a>
            </div>
        </div>
    </div>
    </section>
    <!-- /.content -->
@endsection
