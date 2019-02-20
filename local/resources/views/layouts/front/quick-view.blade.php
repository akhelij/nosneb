<style>
   .description > p  {
       font-size:15px;
       padding:2%;
   } 
</style>

<div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb"  style="border-radius:0px;text-align:center;  padding:3%;  margin-bottom: 0;">
                    <h1 style="text-align:center;">
                        {{ $product->name }}
                       
                    </h1>
                    
                        <small>Ref {{ $product->sku }} </small>
                </ol>
            </div>
            
              
               <div class="col-md-12 " >
                  

                    <div class="col-md-12">
                        <div class="product-description">
                            <div class="col-sm-12 col-xs-12">
                            <h4 >{{ number_format($product->price * $currency_diff, 2) }} {{$currency}}</h4>
                            <div class="description" >{!! $product->description !!}</div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="text-center" style="padding: 2%">
                                    @if($product->type == "off")
                                        <?php $starter = 39; ?>
                                    @else
                                        <?php $starter = 34; ?>
                                    @endif
                                    @foreach ($product->sizes as $key => $size)
                                        @if($size->value > 0)
                                            @if($key % 2 == 0)
                                                <button class="btn size btn-default" quantity="{{ $size->value }}">{{ $starter+$key }}</button>
                                            @else
                                                <button class="btn size btn-default" quantity="{{ $size->value }}">{{ $starter+$key-0.5 }}</button>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        <div class="excerpt">
                            <hr>{!!  $product->description !!}
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
                                        max="0"
                                        class="form-control"
                                        name="quantity"
                                        id="quantity"
                                        placeholder="Quantité"
                                        value="{{ old('quantity') }}" />
                                    <input type="hidden" name="product" value="{{ $product->id }}" />
                                    <input type="hidden" name="size" id="size"/>
                                    
                                </div>
                                <button type="submit" class="btn btn-warning col-md-6 col-sm-12 col-xs-12"><i class="fa fa-cart-plus"></i> Ajouter au panier
                                </button>
                                <p id="alert-quantity" style="color:red;font-size:10px"></p>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
             
            </div>



</div>
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
                    $(this).attr('class','btn size btn-default');
                })

                $(this).attr('class','btn size btn-primary');
                $('#size').val($(this).text());
                $("#quantity").attr('max',$(this).attr('quantity'));
                $("#quantity").attr('min',1);
                $("#quantity").val(1);
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
@endsection