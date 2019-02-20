@extends('layouts.front.app')

@section('content')
    <style>
       td 
        {
             
             margin: auto;
        }
    </style>
    <div class="container product-in-cart-list">
        @if(!$products->isEmpty())
            <form action="{{ route('checkout.store') }}" method="post" class="form-horizontal">
                {{ csrf_field() }}
                <div class="row" style="margin:0;background-color: #fff;padding-bottom:20px"> 
                    <div class="row" style="margin:0">
                        <ol class="breadcrumb" style="border-radius:0">
                            <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Accueil</a></li>
                            <li class="active">Shopping Cart</li>
                        </ol>
                    </div>
                    <div class="col-md-12 content">
                        <div class="box-body">
                            @include('layouts.errors-and-messages')
                        </div>
                        @if(count($addresses) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <h3><i class="fa fa-map-marker text-success"></i> Adresse de livraison</h3>
                                    <table class="table table-striped ">
                                        <thead>
                                            <th class="col-md-4">Alias</th>
                                            <th class="col-md-4">Adresse</th>
                                            <th class="col-md-4">Côchez la case qui vous convient</th>
                                        </thead>
                                        <tbody>
                                        @foreach($addresses as $address)
                                            <tr>
                                                <td>{{ $address->alias }}</td>
                                                <td>
                                                    {{ $address->address_1 }} {{ $address->address_2 }} <br />
                                                    @if(!is_null($address->province_id) || !is_null($address->city_id))
                                                        {{ $address->city_id }} {{ $address->province_id }} <br />
                                                    @endif
                                                    {{ $address->country_id }} {{ $address->zip }}
                                                </td>
                                                <td align="center">
                                                    <label class="col-md-2 col-md-offset-3">
                                                        <input type="radio" class="form-check-input country" name="billing_address" country="{{ $address->country_id }}" @if($selectedAddress == $address->id) checked="checked"  @endif value="{{ $address->id }}">
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3> <i class="fa fa-truck text-success"></i> Moyen de livraison</h3>
                                    <div class="livraison-hors-maroc" hidden>
                                    @if(!$couriers->isEmpty())
                                        <table class="table ">
                                        <thead>
                                            <th class="col-md-4">Nom</th>
                                            <th class="col-md-4">Coût</th>
                                            <th class="col-md-4"></th>
                                        </thead>
                                        <tbody>
                                            @foreach($couriers as $courier)
                                            <tr>
                                                <td>{{ $courier->name }}</td>
                                                <td>{{ $courier->cost . ' ' . $currency}} </td>
                                                <td align="center">
                                                    <label class="col-md-2 col-md-offset-3">
                                                        <input type="radio" class="form-check-input" name="courier" value="{{ $courier->id }}" @if($selectedCourier == $courier->id) checked="checked"  @endif @if(count($couriers) == 1) checked="checked"  @endif>
                                                    </label>
                                                </td>
                                            </tr>
                                            
                                            @endforeach
                                           
                                        </tbody>
                                    </table>
                                    @else
                                        <p class="alert alert-danger">No courier set</p>
                                    @endif
                                    </div>
                                     <div class="livraison-maroc">
                                        <h4 style="padding:2%">Livraison gratuite au Maroc.</h4>
                                     </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3> <i class="fa fa-money text-success"></i> Methode de paiment</h3>
                                    @if($payments)
                                        <table class="table ">
                                            <thead>
                                            <th class="col-md-8">Nom</th>
                                            <th class="col-md-4"></th>
                                            </thead>
                                            <tbody>
                                            @foreach($payments as $payment)
                                            @if($payment->slug === 'paiement-a-la-livraison')
                                                <tr class="paiement-a-la-livraison">
                                            @else
                                                  <tr>
                                            @endif
                                                    @if($payment->slug === 'paypal')
                                                        <td><img src="{{url('/img/paypal.png')}}"></td>
                                                    @elseif($payment->slug === 'paiement-a-la-livraison')
                                                    <td style="padding:2%" ><img class ="hidden-xs" src="{{url('/img/pod.png')}}" > Paiement à la livraison</td>
                                                    @else
                                                        <td><img class ="hidden-xs" src="{{url('/img/Fpay_paiement_1.png')}}"><img class ="visible-xs" src="{{url('/img/Fpay_paiement_3.png')}}" width="100px"></td>
                                                    @endif
                                                    <td align="center">
                                                        <label class="col-md-2 col-md-offset-3">
                                                            <input type="radio" class="form-check-input payment" method="{{$payment->slug}}" name="payment" value="{{ $payment->id }}" @if($selectedPayment == $payment->id) checked="checked"  @endif>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="alert alert-danger">No payment method set</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="alert alert-danger"><a href="{{ route('customer.address.create', [$customer->id * 15212]) }}">Aucune adresse n'est trouvée.</a></p>
                            <hr>
                             <!-- Main content -->
                            <a href="{{ route('customer.address.create', [$customer->id * 15212]) }}" class="btn btn-success btn-large">Ajouter une adresse</a>
   
                        @endif
                        <hr>
                        <h3><i class="fa fa-cart-plus text-success"></i> Total</h3>
                        <table class="table table-striped">
                            <tbody>
                            <!--<tr>
                                <td class="bg-warning">Subtotal</td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="text-center bg-warning">{{config('cart.currency')}} {{ $subtotal }}</td>
                            </tr>
                            <tr id="shippingRow" @if($shipping == 0)style="display: none"@endif>
                                <td class="bg-warning">Shipping</td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="text-center bg-warning">{{config('cart.currency')}} <span id="shippingFee"> {{ $shipping }}</span></td>
                            </tr>
                            <tr>
                                <td class="bg-warning">Tax</td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"></td>
                                <td class="text-center bg-warning">{{config('cart.currency')}} {{ $tax }}</td>
                            </tr>-->
                            <tr>
                                <td class="bg-success">Total</td>
                                <td class="bg-success"></td>
                                <td class="bg-success"></td>
                                <td class="bg-success"></td>
                            <td class="text-center bg-success"><span id="cartTotal">{{ $total }}</span> {{ $currency }}</td>
                            </tr>
                            </tbody>
                        </table>
                        @if(count($addresses) > 0)
                        
                        <div class="row">
                            <div class="col-md-12">
                                Code de réduction : 
                                <input type="text" id="code" name="code" placeholder="KSJLM" style="color:black">
                            </div>
                            <div class="col-md-12">
                                <div style="padding:1%">
                                <input type="checkbox" name="" id="" style="margin-top:-2px;" required><span > J'accepte les <a href="">conditions générales de vente</a></span>
                               
                               </div>
                                <hr>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <a href="{{ route('cart.index') }}" class="btn btn-default"> <i class="fa fa-shopping-basket"></i> Review cart</a>

                                        <button class="btn btn-primary" id="card" > <i class="fa fa-check"></i> Checkout now</button>
                                        <a href="{{ route('checkout.success') }}" class="btn btn-primary hidden" id="livraison" > <i class="fa fa-check"></i> Checkout now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                
                </div>
                
            </form>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('.country').click(function(){
                if($(this).attr('country') =="Maroc" || $(this).attr('country') =="Morocco"){
                    $('.livraison-maroc').show();
                    $('.paiement-a-la-livraison').show();
                    $('.livraison-hors-maroc').hide();
                    
                }else{
                    $('.livraison-maroc').hide();
                    $('.paiement-a-la-livraison').hide();
                    $('.livraison-hors-maroc').show();
                }
            })
            
            // Payment options
            $('input[name="payment"]').on('change', function () {
                $.ajax({
                    type: 'post',
                    url: '/set-payment',
                    data: { paymentId: $(this).val(), _token: $('input[name="_token"]').val()},
                    success: function(data){
                        //
                    }
                });
            });

            // Addresses
            $('input[name="address"]').on('change', function () {
                $.ajax({
                    type: 'post',
                    url: '/set-address',
                    data: { addressId: $(this).val(), _token: $('input[name="_token"]').val()},
                    success: function(data){
                        //
                    }
                });
            });

            // Couriers
            $('input[name="courier"]').on('change', function () {
                $.ajax({
                    type: 'post',
                    url: '/set-courier',
                    data: { courierId: $(this).val(), _token: $('input[name="_token"]').val()},
                    success: function(data){
                        console.log(data);
                        $(data.courier).each(function (idx, shipping) {
                            $('#shippingFee').html(shipping.cost);
                            if (shipping.cost != 0) {
                                $('#shippingRow').fadeIn();
                            } else {
                                $('#shippingRow').fadeOut();
                            }
                        });
                        $('#cartTotal').html(data.cartTotal);
                    }
                });
            });
        });
    </script>
     <!-- /.content -->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        var currentcity_id=[];

  	url="{{ asset('js/countries.json') }}";
  	$.getJSON(url,function(countriesArray)
  	{
  	  $("#country_id option").remove();
	    //loop through country_id..
	    $.each(countriesArray,function(key,countries)
	    {
				$.each(countries,function(key,country)
	    					{ 
								if(country.name == "Morocco"){
									$("<option selected></option>")
	         						.attr("value",country.name)
	         						.attr("country_id",country.id)
	         						.append(country.name)
	                     			.appendTo($("#country_id"));
								}else{
									$("<option></option>")
	         						.attr("value",country.name)
	         						.attr("country_id",country.id)
	         						.append(country.name)
	                     			.appendTo($("#country_id"));
								}
							 });
               
            
	       
	    }); 
	    // trigger "change" to fire the #state section update process
	    $("#country_id").trigger("change");
	    

  	});    
    $("#country_id").on("change",function()
  	{
  	
  		countryCode=$('option:selected', this).attr('country_id');
  		
  		// Populate country select box from battuta API
	    url="{{ asset('js/states.json') }}";

  		$.getJSON(url,function(regionsArray)
  		{
			 $("#province_id option").remove();
		    //loop through regions..
			$.each(regionsArray,function(key,regions)
		    {
				
				regions = regions.filter(region => region.country_id === countryCode);
				
				$.each(regions,function(key,region)
				{
					
						$("<option ></option>")
									.attr("value",region.name)
									.attr("province_id",region.id)
									.append(region.name)
									.appendTo($("#province_id"));
					
				});
			});
		    // trigger "change" to fire the #state section update process
	    	$("#province_id").trigger("change");
	    	
	    }); 
	    
  	});
  	$("#province_id").on("change",function()
  	{
  		
  		region=$('option:selected', this).attr('province_id');
	    url="{{ asset('js/cities.json') }}";
		
  		$.getJSON(url,function(citiesArray)
  		{	
			  
			$("#city_id option").remove();
		    //loop through regions..
			$.each(citiesArray,function(key,cities)
		    {
				cities = cities.filter(city => city.state_id === region);
				
				
				$.each(cities,function(key,city)
				{
						if(city.name=="Casablanca"){
									$("<option selected></option>")
									.attr("value",city.name)
									.attr("city_id",city.id)
									.append(city.name)
									.appendTo($("#city_id"));
								}else{
									$("<option ></option>")
									.attr("value",city.name)
									.attr("city_id",city.id)
									.append(city.name)
									.appendTo($("#city_id"));
								}
						
					
				});
			});
		    
	    	
	    }); 
	    
  	})
  	
   //-------------------------------END OF SELECT CASCADING-------------------------//

    </script>
@endsection