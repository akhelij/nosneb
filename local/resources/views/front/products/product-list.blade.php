@if(!empty($products) && !collect($products)->isEmpty())
<section class="row" style="background:#fff ; margin:0">
    <ul class="text-center list-unstyled">
        @if(isset($category3->slug))
            <li class="col-md-3 col-sm-6 col-xs-12 product-list" style="padding:0;min-height:350px;border:1px solid #94b3ca">
                   <div class="product-text" style="    position: absolute;
    top: 30%;
    left:5%;
    width:90%">
                    
                        
                            <h1>NOUVELLE <br> <br>COLLECTION </h1>
                            <div class="col-md-12" style="padding-top:20%">
                                <div class="col-md-7" style="margin-top:4%;border:1px solid #94b3ca"></div>
                                <div class="col-md-5"><p><a  href="{{ route('front.category.slug', $category3->slug) }}" role="button">Afficher tout</a></p></div>
                            </div>
                    </div>
            </li>
            @endif
            
        @foreach($products as $product)
            @if($product->status == 1)
                
            <li class="col-md-3 col-sm-6 col-xs-12 product-list" @if($product->quantity == 0)
                     style='opacity:0.5'
                @endif>
                
                <div class="single-product" style="background-image : url('{{ url("storage/$product->cover") }}');">
                    <div class="product" align="center" style="margin:0">
                        <div class="product-overlay" style="margin-top: 20%">
                            <div class="vcenter" >
                                <div class="centrize">
                                    <ul class="list-unstyled list-inline">
                                       <!-- <li>
                                            <form action="{{ route('cart.store') }}" class="form-inline" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="quantity" value="1" />
                                                <input type="hidden" name="product" value="{{ $product->id }}">
                                                <button id="add-to-cart-btn" type="submit" class="btn btn-primary" data-toggle="modal" data-target="#cart-modal" title="Ajouter au panier"> <i class="fa fa-cart-plus"></i> </button>
                                            </form>
                                        </li>
                                        <li>  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_{{ $product->id }}"  title="Vue rapide"> <i class="fa fa-cart-plus"></i> </button>-->
                                        <li>  <a class="btn btn-primary product-btn" href="{{ route('front.get.product', str_slug($product->slug)) }}" style="background:#2a4672" title="Plus de détails">  <i class="fa fa-eye"></i></a> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <a href="{{ route('front.get.product', str_slug($product->slug)) }}"  >
                    <div class="product-text" >
                    
                        
                            <h3>{{ $product->name }}</h3>
                            <h5>Ref {{ $product->sku }}</h5>
                            <div class="col-md-12" style="margin-top:3%">
                                <div class="col-md-7" style="margin-top:4%;border:1px solid #94b3ca"></div>
                                
                                    @if($product->old_price !=null)
                                    <div class="col-md-5">
                                        <p>{{ number_format($product->old_price * $currency_diff, 2) }}  <span style="font-size:8px">{{$currency}}</span></p>
                                        <del> <p style="font-size:8px;margin-top:-5%">{{ number_format($product->price * $currency_diff, 2) }}  <span >{{$currency}}</span></p></del>
                                       
                                    </div>
                                    @else
                                    <div class="col-md-5">
                                      <p>{{ number_format($product->price * $currency_diff, 2) }}  <span style="font-size:8px">{{$currency}}</span></p>
                                    </div>
                                    @endif
                                
                            </div>
                    </div>
                    
                </a> 
                    <!-- Modal -->
                    <div class="modal fade" id="myModal_{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                @include('layouts.front.quick-view')
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @endif
        @endforeach
        @if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left">{{ $products->links() }}</div>
                </div>
            </div>
        @endif
    </ul>
</section>
@else
    <p class="alert alert-warning">Aucun produit n'est disponible.</p>
@endif