@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Dashboard</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-12">
                 Welcome to the dashboard will be here
                </div>
                <br>
                <div class="col-md-12" style="text-align:center">
                    <h2><i class="fa fa-dropbox"> </i> Stock</h2>
                    <table>
                        @if(!$products->isEmpty())
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Name</td>
                                    <td>Ref</td>
                                    <td>39</td>
                                    <td>39,5</td>
                                    <td>40</td>
                                    <td>40,5</td>
                                    <td>41</td>
                                    <td>41,5</td>
                                    <td>42</td>
                                    <td>42,5</td>
                                    <td>43</td>
                                    <td>43,5</td>
                                    <td>44</td>
                                    <td>44,5</td>
                                    <td>45</td>
                                    <td>45,5</td>
                                    <td>46</td>
                                    <td>Quantity</td>
                                    
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $product)
                                @if(count($product->categories)>0 && $product->categories[0]->name == "Collection")
                                    @if($product->quantity==0)
                                         <tr style="background-color:crimson;color:white">
                                    @else
                                            <tr >
                                    @endif
                                        <td>{{ $product->id }}</td>
                                            <td><a href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }} @if(count(explode(":",$product->description)) > 2) {!! explode(":",$product->description)[3] !!}@else ??? @endif</a></td>
                                        <td>
                                            {{ $product->sku }} / 
                                            
                                                @if(count($product->categories)>0 && $product->categories[3]->name !="Formes") 
                                                    {{ $product->categories[3]->name }}
                                                @elseif(count($product->categories)>0 && $product->categories[3]->name =="Formes") 
                                                   {{ $product->categories[4]->name }}
                                                @else {{""}}
                                                @endif
                                           
                                        </td>
                                      
                                            @foreach ($product->sizes as $key => $size)
                                            
                                            <td align="center" style="font-size:10px">{!! $size->value !!}</td>
                                            
                                            @endforeach
                                        
                                        <td>
                                            {{ $product->quantity }}
                                        </td>
                                        
                                        <td>@include('layouts.status', ['status' => $product->status])</td>
                                    
                                    </tr>
                               
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                Footer
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
@endsection
