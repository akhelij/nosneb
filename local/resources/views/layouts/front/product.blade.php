<style>
   .description > p  {
       font-size:15px;
       padding:2%;
   }
</style>

<div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb" style="border-radius:0px;text-align:center;  padding:3%;  margin-bottom: 0;">
                    <h1>
                        {{ $product->name }}

                    </h1>
                    @if($product->categories[0]->slug == "accessoires")
                        <small> {{ $product->sku }} </small>
                    @else
                        <small>Ref {{ $product->sku }} </small>
                    @endif
                </ol>
            </div>
            <div class="col-md-12"  style="margin:0;">
              <div class="col-md-8 first-view" style="background-color:white;background-image : url('{{ asset("storage/$product->cover") }}');">

              </div>
               <div class="col-md-4 info" style="overflow-x: auto;">


                    <div class="col-md-12">
                        @if(session()->has('demande'))
                         <div class="text-center alert alert-success">

                                    <p>Rupture du stock</p>
                                    <p>Nous reviendrons vers vous dés que la paire sera disponible</p>
                         </div>
                        @elseif($product->quantity==0)
                         <div class="text-center alert alert-danger" style="margin-bottom:20%">

                                      <p>Rupture du stock</p>
                                    <p style="font-size:12px">Si vous êtes intéressés par ce modèle, veuillez saisir votre pointure, nous reviendrons vers vous dès quel sera disponible.</p>
                                    <form action="{{ route('product.demande') }}" class="form-inline " method="post">

                                        {{ csrf_field() }}
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12 ">
                                            <input type="number"
                                                style="width:100%;"
                                                min="0"
                                                class="form-control"
                                                name="size"
                                                id="size"
                                                placeholder="Votre pointure" style="font-size:14px !important"/>
                                           </div>
                                           <hr>
                                            <button type="submit" class="btn btn-warning col-md-12 col-sm-12 col-xs-12"  style="font-size:14px"> M'informer dés que la paire sera disponible
                                            </button>


                                    </form>

                         </div>
                        @endif
                        <div class="product-description">
                            <div class="col-sm-12 col-xs-12">
                            @if($product->old_price !=null)
                            <h4 >{{ number_format($product->old_price * $currency_diff, 2) }} {{$currency}}</h4>
                            <del>{{ number_format($product->price * $currency_diff, 2) }} {{$currency}}</del>
                           @else
                             <h4 >{{ number_format($product->price * $currency_diff, 2) }} {{$currency}}</del>
                           @endif
                            
                                    <div class="description" >{!! $product->description !!}</div>
                            </div>
                            @if(count($product_linked)>1)

                            <div class="col-sm-12 col-xs-12">
                                <h4>Autres couleurs : </h4>
                                @foreach ($product_linked as $item)
                                @if($item->slug != $product->slug)
                                <div class="col-md-4 " style="background-color:white;">
                                    <a href="{{ route('front.get.product', str_slug($item->slug)) }}">
                                      <img src="{{ asset("storage/$item->cover") }}" style=" width:100px; height : 55px" >
                                    </a>
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @endif
                             @if($product->quantity!=0)
                            <div class="col-sm-12 col-xs-12" >
                            <h4>Pointures : </h4>
                                <div class="text-center" style="padding: 2%">
                                    @if($product->type == "off")
                                        <?php $starter = 39;
$starterUK = 5;?>
                                    @else
                                        <?php $starter = 34;
$starterUK = 5;?>
                                    @endif

                                    @foreach ($product->sizes as $key => $size)

                                            @if($key % 2 == 0)
                                                @if($size->value > 0)
                                                     <button class="btn size btn-default btn-xs" quantity="{{ $size->value }}">{{ "UK ".$starterUK."| EU ".$starter}}</button>

                                                 @endif
                                            @else
                                                 @if($size->value > 0)
                                                     <button class="btn size btn-default btn-xs" quantity="{{ $size->value }}">{{ "UK ".($starterUK+0.5)."| EU ".($starter+0.5) }}</button>

                                                 @endif
                                                 <?php $starter = $starter + 1;
$starterUK = $starterUK + 1?>
                                          @endif
                                    @endforeach

                                </div>

                            </div>

                            <hr>

                            <div class="row">
                                <div class=" col-xs-12 ">
                                    @include('layouts.errors-and-messages')
                                    <form action="{{ route('cart.store') }}" class="form-inline" method="post">
                                        {{ csrf_field() }}
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12 ">
                                            <input type="number"
                                                style="width:100%;"

                                                min="0"

                                                @if($product->categories[0]->slug=="accessoires" && $product->slug != "embauchoirs")
                                                max="{{$product->quantity}}"
                                                @else
                                                max="0"
                                                @endif

                                                class="form-control"
                                                name="quantity"
                                                id="quantity"
                                                placeholder="Quantité"
                                                value="{{ old('quantity') }}" />
                                            <input type="hidden" name="product" value="{{ $product->id }}" />
                                        <input type="hidden" name="size" id="size" @if($product->categories[0]->slug=="accessoires" && $product->slug != "embauchoirs")
                                                value="0" @endif/>
                                        </div>
                                        <button type="submit" class="btn btn-warning col-md-6 col-sm-12 col-xs-12"><i class="fa fa-cart-plus"></i> Ajouter au panier
                                        </button>
                                        <p id="alert-quantity" style="color:red;font-size:10px"></p>
                                    </form>
                                </div>
                            </div>
                               @endif
                        </div>
                    </div>
                </div>
            </div>



<!--
    <div class="col-md-6">
        <ul id="thumbnails" class="col-md-4 list-unstyled">
            <li>
                <a href="javascript: void(0)">
                    @if(isset($product->cover))
                    <img class="img-responsive img-thumbnail"
                         src="{{ asset("storage/$product->cover") }}"
                         alt="{{ $product->name }}" />
                    @else
                    <img class="img-responsive img-thumbnail"
                         src="{{ asset("https://placehold.it/180x180") }}"
                         alt="{{ $product->name }}" />
                    @endif
                </a>
            </li>
            @if(isset($images) && !$images->isEmpty())
                @foreach($images as $image)
                <li>
                    <a href="javascript: void(0)">
                    <img class="img-responsive img-thumbnail"
                         src="{{ asset("storage/$image->src") }}"
                         alt="{{ $product->name }}" />
                    </a>
                </li>
                @endforeach
            @endif
        </ul>
        <figure class="text-center product-cover-wrap col-md-8">
            @if(isset($product->cover))
                <img id="main-image" class="product-cover img-responsive"
                     src="{{ asset("storage/$product->cover") }}?w=400"
                     data-zoom="{{ asset("storage/$product->cover") }}?w=1200">
            @else
                <img id="main-image" class="product-cover" src="https://placehold.it/300x300"
                     data-zoom="{{ asset("storage/$product->cover") }}?w=1200" alt="{{ $product->name }}">
            @endif
        </figure>
    </div>
-->

  @if(isset($images) && !$images->isEmpty())
    <div class="col-md-12">
        <ol class="breadcrumb" style="border-radius:0px;text-align:center; padding:3%;  margin-bottom: 0;">
            <h2>
                Plus d'images
            </h2>

        </ol>
    </div>
    <div class="col-md-12 " align="center">
        <div class="col-md-12" style="margin:0;padding:0">
                        @foreach($images as $image)

                        <div class="col-md-6 other-view" style="margin:0;background-image : url('{{ asset("storage/$image->src") }}');">

                        </div>

                        @endforeach

         </div>


    </div>
     @endif
</div>
@include('mailchimp::mailchimp')
@section('css')
    <link rel="stylesheet" href="{{ asset('front/css/drift-basic.min.css') }}">
    <style type="text/css">
        .product-cover-wrap {
            border: 1px solid #eee;
        }

        .product-description {
            position: relative;
        }

        .excerpt {
            display: none;
        }

        .modal-dialog .modal-content {
            min-width: 800px;
        }

        .modal-dialog h1 {
            font-size: 18px;
            text-align: left;
            line-height: 24px;
        }

        .modal-dialog h1 small {
            display: block;
            padding-top: 10px;
        }

        .modal-dialog .description,
        .modal-dialog .excerpt {
            font-size: 14px;
            line-height: 16px;
            text-align: left;
        }

        .modal-dialog .description {
            display: none;
        }

        .modal-dialog #quantity {
            width: 85px;
        }

        .modal-dialog .modal-content {
            padding: 15px;
        }

        .modal-content .excerpt {
            display: block;
            text-align: left;
        }

        #thumbnails li {
            margin-bottom: 10px;
        }

        #thumbnails li img {
            width: 100px;
        }

        #thumbnails li a:hover img {
            border: 1px solid #d89522;
        }
    </style>
@endsection
@section('js')
    <script type="text/javascript" src="{{ asset('front/js/Drift.min.js') }}"></script>
    @if($product->categories[0]->slug=="accessoires" && $product->slug != "embauchoirs")

    <script type="text/javascript">

        $(document).ready(function () {

            var productPane = document.querySelector('.product-cover');
            var paneContainer = document.querySelector('.product-cover-wrap');

            new Drift(productPane, {
                paneContainer: paneContainer,
                inlinePane: false
            });

            $('#thumbnails li img').on('click', function () {
                $('#main-image')
                        .attr('src', $(this).attr('src') +'?w=400')
                        .attr('data-zoom', $(this).attr('src') +'?w=1200');
            });

        });


        $('#quantity').on('change', function(){

            if($("#quantity").attr('max') == $("#quantity").val()){

                $('#alert-quantity').text('Quantité maximum atteinte');
            }
            else if($("#quantity").val() > parseInt($("#quantity").attr('max'))){
                $("#quantity").val($("#quantity").attr('max'));
                $('#alert-quantity').text('Vous ne pouvez pas dépasser la quantité disponible');
            }
            else if($("#quantity").val() < 0){
                $("#quantity").val($("#quantity").attr('min'));
                 $('#alert-quantity').text('');
            }

        });
    </script>
    @else
<script type="text/javascript">
        $(document).ready(function () {

            var productPane = document.querySelector('.product-cover');
            var paneContainer = document.querySelector('.product-cover-wrap');

            new Drift(productPane, {
                paneContainer: paneContainer,
                inlinePane: false
            });

            $('#thumbnails li img').on('click', function () {
                $('#main-image')
                        .attr('src', $(this).attr('src') +'?w=400')
                        .attr('data-zoom', $(this).attr('src') +'?w=1200');
            });
        });

        $('.size').on('click', function(){
            if($(this).attr('class')){
                $('.size').each(function(){
                    $(this).attr('class','btn size btn-default btn-xs');
                })

                $(this).attr('class','btn size btn-primary btn-xs');
                $("#quantity").attr('max',$(this).attr('quantity'));
                $("#quantity").attr('min',1);
                $("#quantity").val(1);

                $('#size').val($(this).text().split("EU ")[1]);
                $('#alert-quantity').text('');

                if($("#quantity").attr('max') == $("#quantity").val()){
                    $('#alert-quantity').text('Il ne reste qu\'une seule paire');
                }
            }
        })

        $('#quantity').on('change', function(){
            if($("#quantity").attr('max') == 0)
            {
                $("#quantity").val(0);
                $('#alert-quantity').text('Veuillez choisir une pointure');
            }
            else if($("#quantity").attr('max') == $("#quantity").val()){

                $('#alert-quantity').text('Quantité maximum atteinte');
            }
            else if($("#quantity").val() > parseInt($("#quantity").attr('max'))){
                $("#quantity").val($("#quantity").attr('max'));
                $('#alert-quantity').text('Vous ne pouvez pas dépasser la quantité disponible');
            }
            else if($("#quantity").val() < 0){
                $("#quantity").val($("#quantity").attr('min'));
                 $('#alert-quantity').text('');
            }

        });
    </script>
    @endif
@endsection