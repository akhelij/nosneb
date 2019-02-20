@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
        @include('layouts.errors-and-messages')
        <div class="box">
            <form action="{{ route('admin.addresses.store') }}" method="post" class="form" enctype="multipart/form-data">
                <div class="box-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="customer">Customers </label>
                        <select name="customer" id="status" class="form-control">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alias">Alias <span class="text-danger">*</span></label>
                        <input type="text" name="alias" id="alias" placeholder="Home or Office" class="form-control" value="{{ old('alias') }}">
                    </div>
                    <div class="form-group">
                        <label for="address_1">Address 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_1" id="address_1" placeholder="Address 1" class="form-control" value="{{ old('address_1') }}">
                    </div>
                    <div class="form-group">
                        <label for="address_2">Address 2 </label>
                        <input type="text" name="address_2" id="address_2" placeholder="Address 2" class="form-control" value="{{ old('address_2') }}">
                    </div>
                    <div class="form-group">
                        <label for="country_id">Country </label>
                        <select name="country_id" id="country_id" class="form-control">
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="province_id">Province </label>
                        <select name="province_id" id="province_id" class="form-control">
                           
                        </select>
                    </div>
                    <div id="cities" class="form-group">
                        <label for="city_id">City </label>
                        <select name="city_id" id="city_id" class="form-control">
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="zip">Zip Code </label>
                        <input type="text" name="zip" id="zip" placeholder="Zip code" class="form-control" value="{{ old('zip') }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status </label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">Disable</option>
                            <option value="1">Enable</option>
                        </select>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="btn-group">
                        <a href="{{ route('admin.addresses.index') }}" class="btn btn-default">Back</a>
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
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        var currentcity_id=[];
// This is a demo API key that can only be used for a short period of time, and will be unavailable soon. You should rather request your API key (free)  from http://battuta.medunes.net/ 	
var BATTUTA_KEY="3917b3467263d81ac67f1dc23d1e2cc0"
  	// Populate country select box from battuta API
	url="https://battuta.medunes.net/api/country/all/?key="+BATTUTA_KEY+"&callback=?";
  	$.getJSON(url,function(country_id)
  	{
  		
      $("#country_id option").remove();
	    //loop through country_id..
	    $.each(country_id,function(key,country)
	    {
           
                $("<option></option>")
	         				.attr("value",country.code)
	         				.append(country.name)
	                     	.appendTo($("#country_id"));
            
	       
	    }); 
	    // trigger "change" to fire the #state section update process
	    $("#country_id").trigger("change");
	    

  	});
    
    $("#country_id").on("change",function()
  	{
  	
  		countryCode=$("#country_id").val();
  		
  		// Populate country select box from battuta API
	    url="https://battuta.medunes.net/api/region/"
	    +countryCode
	    +"/all/?key="+BATTUTA_KEY+"&callback=?";

  		$.getJSON(url,function(regions)
  		{
  			$("#province_id option").remove();
		    //loop through regions..
		    $.each(regions,function(key,region)
		    {
                
                    $("<option ></option>")
		         				.attr("value",region.region)
		         				.append(region.region)
		                     	.appendTo($("#province_id"));
                
		    });
		    // trigger "change" to fire the #state section update process
	    	$("#province_id").trigger("change");
	    	
	    }); 
	    
  	});
  	$("#province_id").on("change",function()
  	{
  		
  		// Populate country select box from battuta API
  		countryCode=$("#country_id").val();
		region=$("#province_id").val();
	    url="https://battuta.medunes.net/api/city/"
	    +countryCode
	    +"/search/?region="
	    +region
	    +"&key="
	    +BATTUTA_KEY
	    +"&callback=?";
  		
  		$.getJSON(url,function(city_id)
  		{
  			currentcity_id=city_id;
        	var i=0;
        	$("#city_id option").remove();
        
		    //loop through regions..
		    $.each(city_id,function(key,city)
		    {
                
                    $("<option ></option>")
                                .attr("value",city.city)
		         				.append(city.city)
		                        .appendTo($("#city_id"));
                
		        
		         				
		    });
		    // trigger "change" to fire the #state section update process
	    	$("#city_id").trigger("change");
	    	
	    }); 
	    
  	});	
  	
   //-------------------------------END OF SELECT CASCADING-------------------------//

    </script>
@endsection