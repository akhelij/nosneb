@extends('layouts.front.app')

@section('content')
        <div class="container product-in-cart-list " >
            @if(!empty($products) && !collect($products)->isEmpty())
                <div class="row"  style="margin:0;background-color: #fff;padding-bottom:20px"> 
                    <div class="row" style="margin:0">
                        <ol class="breadcrumb" style="border-radius:0">
                            <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Home</a></li>
                            <li><a href="#">Category</a></li>
                            <li class="active">Product</li>
                        </ol>
                    </div>
                    <div class="col-md-12 content" style="">
                        <div class="box-body">
                            @include('layouts.errors-and-messages')
                        </div>
                        <h2><i class="fa fa-cart-plus"></i> Shopping Cart</h2>
                        <hr>
                        <table class="table table-striped">
                            <thead>
                                <th class="col-md-2">Cover</th>
                                <th class="col-md-3">Name</th>
                                <th class="col-md-3">Pointure</th>
                                <th class="col-md-2">Quantité</th>   
                                <th class="col-md-1"></th>   
                                <th class="col-md-1">Price</th>
                            </thead>
                            <tfoot>
                           <!-- <tr>
                                <td class="bg-warning">Subtotal</td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning">{{$currency}} {{ $subtotal }}</td>
                            </tr>-->
                            @if(isset($shippingFee) && $shippingFee != 0)
                            <tr>
                                <td class="bg-warning">Shipping</td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning">{{$currency}} {{ $shippingFee }}</td>
                            </tr>
                            @endif
                          
                            <tr>
                                <td class="bg-success">Total</td>
                                <td class="bg-success"></td>
                                <td class="bg-success"></td>
                                <td class="bg-success"></td>
                                <td class="bg-success"></td>
                                <td class="bg-success">{{ $total }} {{$currency}} </td>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <a href="{{ route('front.get.product', [$product->slug]) }}" class="hover-border">
                                            @if(isset($product->cover))
                                                <img src="{{ asset("storage/$product->cover") }}" alt="{{ $product->name }}" class="img-responsive img-thumbnail">
                                            @else
                                                <img src="https://placehold.it/120x120" alt="" class="img-responsive img-thumbnail">
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>@if($product->size == 0 ) --- @else {{ $product->size }} @endif</td>
                                    <td>{{ $product->qty }}
                                      <!--  <form action="{{ route('cart.update', $product->rowId) }}" class="form-inline" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="put">
                                            <div class="form-group">
                                                <input type="text" name="quantity" value="{{ $product->qty }}" class="form-control" />
                                            </div>
                                            <button class="btn btn-default btn-block">Update</button>
                                        </form>-->
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.destroy', $product->rowId) }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="delete">
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-danger"><i class="fa fa-times"></i></button>
                                        </form>
                                    </td>
                                    <td>{{ number_format($product->price * $currency_diff, 2) }} {{$currency}} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="btn-group pull-right">
                                    <a href="{{ route('home') }}" class="btn btn-default">Continuer votre shopping</a>
                                    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <p class="alert alert-warning">Votre panier est vide. <a href="{{ route('catalog') }}">Acheter maintenant!</a></p>
                    </div>
                </div>
            @endif
        </div>
@endsection

@section('js')



<!-- Event snippet for Panier / Achat conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-775985184/b8lUCL7H6ZIBEKCwgvIC',
      'transaction_id': ''
  });
</script>


@endsection