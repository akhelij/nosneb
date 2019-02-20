@extends('layouts.front.app')

@section('content')
<hr>
    <!-- Main content -->
    <section class="container content">
		<div class="col-md-8 col-md-offset-2" style="background-color:#ffffff;padding:5%">
			@include('layouts.errors-and-messages')
			 <h2>Ajouter une nouvelle adresse</h2>
            <hr>
			<div class="box">
				<form action="{{ route('customer.address.store', $customer->id) }}" method="post" class="form" enctype="multipart/form-data">
					<input type="hidden" name="status" value="1">
					<div class="box-body">
						{{ csrf_field() }}
						<div class="form-group hidden">
							<label for="alias">Alias <span class="text-danger">*</span></label>
							<input type="text" name="alias" id="alias" placeholder="Home or Office" class="form-control" value="home">
						</div>
						<div class="form-group">
							<label for="country_id">Pays </label>
							<select name="country_id" id="country_id" country_id="" class="form-control ">

							</select>
						</div>
						<div class="form-group">
							<label for="province">Région </label>

							<input  name="province_id" id="province_id"  province_id ="" class="form-control "/>
						</div>
						<div class="form-group">
							<label for="city">Ville </label>

							<input  name="city_id" id="city_id" city_id="" class="form-control "/>
						</div>

						<div class="form-group">
							<label for="zip">Code postal</label>
							<input type="text" name="zip" id="zip" placeholder="Zip code" class="form-control" value="{{ old('zip') }}">
						</div>
						<div class="form-group">
                                        <label for="phone" >Télèphone  <span class="text-danger">*</span></label>
										<input type="phone" name="phone" id="phone" placeholder="Telephone" class="form-control" value="{{ old('phone') }}">
                        </div>
						<div class="form-group">
							<label for="address_1">Adresse 1 <span class="text-danger">*</span></label>
							<input type="text" name="address_1" id="address_1" placeholder="Adresse" class="form-control" value="{{ old('address_1') }}">
						</div>
						<div class="form-group">
							<label for="address_2">Adresse 2 </label>
							<input type="text" name="address_2" id="address_2" placeholder="Complement d'adresse " class="form-control" value="{{ old('address_2') }}">
						</div>

					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<div class="btn-group">
							<button type="submit" class="btn btn-primary">Créer</button>
						</div>
					</div>
				</form>
			</div>
			<!-- /.box -->
		</div>
    </section>
    <!-- /.content -->
@endsection

@section('css')
    <link href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('js')
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
								if(country.name=="Maroc"){
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
	    //$("#country_id").trigger("change");


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
