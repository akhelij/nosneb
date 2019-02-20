@extends('layouts.front.app')

@section('content')
<hr>
<div class="container content" >
      <div class="col-md-8 col-md-offset-2" style="background-color:#fff;padding:5%;margin-bottom:20px">
        <div class="col-md-12">@include('layouts.errors-and-messages')</div>
        <div class="col-md-12">
            <h2>Inscription</h2>
            <hr>
            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nom</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail </label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Mot de passe</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmer le mot de passe</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:2%">
                           
                            <div class="col-md-12">
                                 <p>
                                    <input type="checkbox" name="terms" id="terms" style="margin-top:-2px" required> J'accepte les <a href="{{route('terms')}}">conditions générales d'utilisation</a>.
                                </p>
                                <!--<p>
                                    <input type="checkbox" name="newsletter" id="newsletter" style="margin-top:-2px" > Je m'inscris à la newsletter pour recevoir mon code de réduction de 5% et profitez des prochaines offres Benson shoes.
                                </p>-->
                                <button type="submit" id="submit" class="btn btn-primary" style="width:100%">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
           
                    <div class="row">
                        <hr>
                        <a href="{{route('password.request')}}">J'ai oublié mon mot de passe</a><br>
                        <a href="{{route('login')}}" class="text-center">Vous disposez dèjà d'un compte ? Connectez vous.</a>
                    </div>
                </div>
    </div>
   
</div>
@endsection

@section('js')
  

<!-- Event snippet for Formulaire Inscription conversion page -->
<script>
  gtag('event', 'conversion', {'send_to': 'AW-775985184/i_fQCKjp9ZIBEKCwgvIC'});
</script>


@endsection