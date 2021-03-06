@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.products.update', $product->id) }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="row">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="col-md-12">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist" id="tablist">
                                <li role="presentation" @if(!request()->has('combination')) class="active" @endif><a href="#info" aria-controls="home" role="tab" data-toggle="tab">Info</a></li>
                                <li role="presentation" @if(request()->has('combination')) class="active" @endif><a href="#combinations" aria-controls="profile" role="tab" data-toggle="tab">Combinations</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content" id="tabcontent">
                                <div role="tabpanel" class="tab-pane @if(!request()->has('combination')) active @endif" id="info">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h2>{{ ucfirst($product->name) }}</h2>
                                            <div class="form-group">
                                                <label for="sku">SKU <span class="text-danger">*</span></label>
                                                <input type="text" name="sku" id="sku" placeholder="xxxxx" class="form-control" value="{!! $product->sku !!}">
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{!! $product->name !!}">
                                            </div>
                                            <div class="form-group">
                                                <label for="slug">Slug <span class="text-danger">* To have a uniq slug add "-color of the shoe" to the end without spaces exampl : patrick-vegano or harry-cognac </span></label>
                                                <input type="text" name="slug" id="slug" placeholder="Slug" class="form-control" value="{!! $product->slug !!}">
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description </label>
                                                <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{!! $product->description  !!}</textarea>
                                            </div>
                                             <div class="form-group">
                                                <label for="description">Construction </label>
                                                <input class="form-control" name="construction" id="construction" placeholder="Construction" value="{!!$product->construction !!}"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Semelle </label>
                                                <input class="form-control" name="sole" id="sole" placeholder="Semelle" value="{!!$product->sole  !!}" />
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Couleur </label>
                                                <input class="form-control" name="color" id="color" placeholder="Couleur" value="{!! $product->color  !!}"/>
                                            </div>
                                            <div class="form-group">
                                                @if(isset($product->cover))
                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <img src="{{ asset("storage/$product->cover") }}" alt="" class="img-responsive"> <br />
                                                            <a onclick="return confirm('Are you sure?')" href="{{ route('admin.product.remove.image', ['product' => $product->id, 'image' => substr($product->cover, 9)]) }}" class="btn btn-danger btn-sm btn-block">Remove image?</a><br />
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="row"></div>
                                            <div class="form-group">
                                                <label for="cover">Cover </label>
                                                <input type="file" name="cover" id="cover" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                @foreach($images as $image)
                                                    <div class="col-md-3">
                                                        <div class="row">
                                                            <img src="{{ asset("storage/$image->src") }}" alt="" class="img-responsive"> <br />
                                                            <a onclick="return confirm('Are you sure?')" href="{{ route('admin.product.remove.thumb', ['src' => $image->src]) }}" class="btn btn-danger btn-sm btn-block">Remove?</a><br />
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="row"></div>
                                            <div class="form-group">
                                                <label for="image">Images </label>
                                                <input type="file" name="image[]" id="image" class="form-control" multiple>
                                                <span class="text-warning">You can use ctr (cmd) to select multiple images</span>
                                            </div>
                                             @if( $product->type == "on" )
                                                  <input type="checkbox" name="type" id="type" checked > Femme
                                            @else
                                                  <input type="checkbox" name="type" id="type"> Femme
                                            @endif
                            <div class="form-group">
                                nombre de pointre : {{count($product->sizes)}}
                                <table class="table">

                                <thead>
                                    @if($product->type == "on") 
                                                            <tr class="homme" hidden>
                                                                <td>39</td>
                                                                <td>39,5</td>
                                                                <td>40</td>
                                                                <td>40,5</td>
                                                                <td>41</td>
                                                                <td>41,5</td>
                                                                <td>42</td>
                                                                <td>42,5</td>
                                                                
                                                            </tr>
                                                            <tr class="femme" >
                                                                <td>34</td>
                                                                <td>34,5</td>
                                                                <td>35</td>
                                                                <td>35,5</td>
                                                                <td>36</td>
                                                                <td>36,5</td>
                                                                <td>37</td>
                                                                <td>37,5</td>
                                                            </tr>
                                                    @else
                                                          <tr class="homme" >
                                                                <td>39</td>
                                                                <td>39,5</td>
                                                                <td>40</td>
                                                                <td>40,5</td>
                                                                <td>41</td>
                                                                <td>41,5</td>
                                                                <td>42</td>
                                                                <td>42,5</td>
                                                                
                                                            </tr>
                                                            <tr class="femme" hidden>
                                                                <td>34</td>
                                                                <td>34,5</td>
                                                                <td>35</td>
                                                                <td>35,5</td>
                                                                <td>36</td>
                                                                <td>36,5</td>
                                                                <td>37</td>
                                                                <td>37,5</td>
                                                                
                                                            </tr>
                                                    @endif
                                </thead>
                                <tbody>
                                   
                                    <tr>
                                        @foreach ($product->sizes as $key => $size)
                                            @if($key<8)
                                             <td><input type="number" class="form-control size" name="size{{ $key+1 }}" min="0" value="{!! $size->value !!}"></td>
                                             @else
                                             @break
                                             @endif
                                        @endforeach
                                    </tr>
                                    
                                </tbody>
                                </table>
                                 <table class="table">

                                <thead>
                                    @if($product->type == "on") 
                                                            <tr class="homme" hidden>
                                                                <td>43</td>
                                                                <td>43,5</td>
                                                                <td>44</td>
                                                                <td>44,5</td>
                                                                <td>45</td>
                                                                <td>45,5</td>
                                                                <td>46</td>
                                                            </tr>
                                                            <tr class="femme" >
                                                                <td>38</td>
                                                                <td>38,5</td>
                                                                <td>39</td>
                                                                <td>39,5</td>
                                                                <td>40</td>
                                                                <td>40,5</td>
                                                                <td>41</td>
                                                            </tr>
                                                    @else
                                                          <tr class="homme" >
                                                                <td>43</td>
                                                                <td>43,5</td>
                                                                <td>44</td>
                                                                <td>44,5</td>
                                                                <td>45</td>
                                                                <td>45,5</td>
                                                                <td>46</td>
                                                            </tr>
                                                            <tr class="femme" hidden>
                                                                <td>38</td>
                                                                <td>38,5</td>
                                                                <td>39</td>
                                                                <td>39,5</td>
                                                                <td>40</td>
                                                                <td>40,5</td>
                                                                <td>41</td>
                                                            </tr>
                                                    @endif
                                </thead>
                                <tbody>
                                   
                                   
                                    <tr>
                                        @foreach ($product->sizes as $key => $size)
                                        @if($key>=8)
                                         <td><input type="number" class="form-control size" name="size{{ $key+1 }}" min="0" value="{!! $size->value !!}"></td>
                                         @endif
                                        @endforeach
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                                @if($productAttributes->isEmpty())
                                                    <input
                                                            type="text"
                                                            name="quantity"
                                                            id="quantity"
                                                            placeholder="Quantity"
                                                            class="form-control"
                                                            value="{!! $product->quantity  !!}"
                                                    >
                                                @else
                                                    <input type="hidden" name="quantity" value="{{ $qty }}">
                                                    <input type="text" value="{{ $qty }}" class="form-control" disabled>
                                                @endif
                                                @if(!$productAttributes->isEmpty())<span class="text-danger">Note: Quantity is disabled. Total quantity is calculated by the sum of all the combinations.</span> @endif
                                            </div>
                                            <div class="form-group">
                                                @if($productAttributes->isEmpty())
                                                    <label for="price">Price <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DH</span>
                                                        <input type="text" name="price" id="price" placeholder="Price" class="form-control" value="{!! $product->price !!}">
                                                    </div>
                                                @else
                                                    <label for="price">Price <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="price" value="{!! $product->price !!}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DH</span>
                                                        <input type="text" id="price" placeholder="Price" class="form-control" value="{!! $product->price !!}" disabled>
                                                    </div>
                                                @endif
                                                @if(!$productAttributes->isEmpty())<span class="text-danger">Note: Price is disabled. Price is derived based on the combination.</span> @endif
                                            </div>
                                            <div class="form-group">
                                                @if($productAttributes->isEmpty())
                                                    <label for="price">Old price <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DH</span>
                                                        <input type="text" name="old_price" id="old_price" placeholder="Old price" class="form-control" value="{!! $product->old_price !!}">
                                                    </div>
                                                @else
                                                    <label for="price">Old price <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="old_price" value="{!! $product->old_price !!}">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DH</span>
                                                        <input type="text" id="old_price" placeholder="Old price" class="form-control" value="{!! $product->old_price !!}" disabled>
                                                    </div>
                                                @endif
                                                @if(!$productAttributes->isEmpty())<span class="text-danger">Note: Price is disabled. Price is derived based on the combination.</span> @endif
                                            </div>
                                            <div class="form-group">
                                                @include('admin.shared.status-select', ['status' => $product->status])
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                        <div class="col-md-4">
                                            <h2>Categories</h2>
                                            @include('admin.shared.categories', ['categories' => $categories, 'ids' => $product])
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="box-footer">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">Back</a>
                                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane @if(request()->has('combination')) active @endif" id="combinations">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @include('admin.products.create-attributes', compact('attributes'))
                                        </div>
                                        <div class="col-md-8">
                                            @include('admin.products.attributes', compact('productAttributes'))
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection
@section('css')
    <style type="text/css">
        label.checkbox-inline {
            padding: 10px 5px;
            display: block;
            margin-bottom: 5px;
        }
        label.checkbox-inline > input[type="checkbox"] {
            margin-left: 10px;
        }
        ul.attribute-lists > li > label:hover {
            background: #3c8dbc;
            color: #fff;
        }
        ul.attribute-lists > li {
            background: #eee;
        }
        ul.attribute-lists > li:hover {
            background: #ccc;
        }
        ul.attribute-lists > li {
            margin-bottom: 15px;
            padding: 15px;
        }
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        function backToInfo() {
            $('#tablist > li:first-child').addClass('active');
            $('#tablist > li:last-child').removeClass('active');

            $('#tabcontent > div:first-child').addClass('active');
            $('#tabcontent > div:last-child').removeClass('active');
        }
        $(document).ready(function () {
            var checkbox = $('input.attribute');
            $(checkbox).on('change', function () {
                var attributeId = $(this).val();
                if ($(this).is(':checked')) {
                    $('#attributeValue' + attributeId).attr('disabled', false);
                } else {
                    $('#attributeValue' + attributeId).attr('disabled', true);
                }
                var count = checkbox.filter(':checked').length;
                if (count > 0) {
                    $('#productAttributeQuantity').attr('disabled', false);
                    $('#productAttributePrice').attr('disabled', false);
                    $('#productAttributePrice').attr('disabled', false);
                    $('#createCombinationBtn').attr('disabled', false);
                    $('#combination').attr('disabled', false);
                } else {
                    $('#productAttributeQuantity').attr('disabled', true);
                    $('#productAttributePrice').attr('disabled', true);
                    $('#createCombinationBtn').attr('disabled', true);
                    $('#combination').attr('disabled', true);
                }
            });
        });
    </script>
<script>
    $('#type').click(function(){
        if(document.getElementById('type').checked){
                        $('.homme').hide();
                        $('.femme').show();
                     }else{
                        $('.homme').show();
                        $('.femme').hide();
                     }
    });

    $(document).ready(function () {
        if($('#quantity').val() == null || $('#quantity').val() == '' ){
            $('#quantity').val(0);
        }
    })

    $('.size').change(function () {
        var sum = 0;
        $('.size').each(function(){
            if(parseInt($(this).val()) > 0)
            sum = parseInt(sum) + parseInt($(this).val());
            
        })
        $('#quantity').val(sum);
        console.log(sum);
    })
</script>
@endsection