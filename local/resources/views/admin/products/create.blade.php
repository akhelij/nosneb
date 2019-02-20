@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.products.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="row">
                        {{ csrf_field() }}
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="sku">SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku" id="sku" placeholder="xxxxx" class="form-control" value="{{ old('sku') }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="Name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug <span class="text-danger">* To have a uniq slug add "-color of the shoe" to the end without spaces exampl : patrick-vegano or harry-cognac </span></label>
                                <input type="text" name="slug" id="slug" placeholder="Slug" class="form-control" value="{{ old('slug') }}">
                            </div>
                            <div class="form-group">
                                <label for="description">Description </label>
                                <textarea class="form-control ckeditor" name="description" id="description" rows="5" placeholder="Description">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="description">Construction </label>
                                <input class="form-control" name="construction" id="construction" placeholder="Construction" value="{{ old('construction') }}"/>
                            </div>
                            <div class="form-group">
                                <label for="description">Semelle </label>
                                <input class="form-control" name="sole" id="sole" placeholder="Semelle" value="{{ old('sole') }}" />
                            </div>
                            <div class="form-group">
                                <label for="description">Couleur </label>
                                <input class="form-control" name="color" id="color" placeholder="Couleur" value="{{ old('color') }}"/>
                            </div>
                            <div class="form-group">
                                <label for="cover">Cover </label>
                                <input type="file" name="cover" id="cover" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="image">Images</label>
                                <input type="file" name="image[]" id="image" class="form-control" multiple>
                                <span class="text-warning">You can use ctr (cmd) to select multiple images</span>
                            </div>
                            <input type="checkbox" name="type" id="type"> FEMME
                            <div class="form-group">
                                <table class="table">
                                <thead>
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
                                </thead>
                                <tbody>
                                   
                                    <tr>
                                        <td><input type="number" class="form-control size" name="size1" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size2" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size3" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size4" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size5" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size6" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size7" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size8" min="0"></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <table class="table">
                                <thead>
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
                                </thead>
                                <tbody>
                                   
                                    <tr>
                                        <td><input type="number" class="form-control size" name="size9" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size10" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size11" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size12" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size13" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size14" min="0"></td>
                                        <td><input type="number" class="form-control size" name="size15" min="0"></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            
                            <div class="form-group">
                                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" placeholder="Quantity" class="form-control" value="{{ old('quantity') }}">
                            </div>
                            <div class="form-group">
                                <label for="price">Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">DH</span>
                                    <input type="text" name="price" id="price" placeholder="Price" class="form-control" value="{{ old('price') }}">
                                </div>
                            </div>
                             <div class="form-group">
                                               
                                                    <label for="price">Old price <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DH</span>
                                                        <input type="text" name="old_price" id="old_price" placeholder="Old price" class="form-control" >
                                                    </div>
                                               
                                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Disable</option>
                                    <option value="1">Enable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h2>Categories</h2>
                            @include('admin.shared.categories', ['categories' => $categories])
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
@endsection

@section('js')
<script>
    $('#genre').click(function(){
        if(document.getElementById('genre').checked){
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

    $('#name').change(function(){
        $('#slug').val($('#name').val().toLowerCase());
    })
</script>
@endsection