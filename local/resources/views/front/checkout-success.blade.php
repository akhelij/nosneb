@extends('layouts.front.app')

@section('content')
    <div class="container product-in-cart-list">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <p class="alert alert-success">Votre commande à été valider! <a href="{{ route('catalog') }}">Afficher plus!</a></p>
            </div>
        </div>
    </div>
@endsection