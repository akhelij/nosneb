<ul class="list-unstyled list-inline nav navbar-nav pull-right">
      <li>
          <a href="{{url('/')}}">Accueil</a>
      </li>
      <li> 
        <a href="{{url('/about')}}">Héritage</a>
      </li>
    @foreach($categories as $category)
        @if($category->name != "Formes" && $category->name != "Spéciale" && $category->name != "Benson Blue Note")
        <li>
            @if($category->children()->count() > 0)
                @include('layouts.front.category-sub', ['subs' => $category->children])
            @else
                <a @if(request()->segment(2) == $category->slug) class="active" @endif href="{{route('front.category.slug', $category->slug)}}">{{$category->name}} </a>
            @endif
        </li>
        @endif
    @endforeach
     <li> 
        <a href="{{url('/shop')}}">Benson dans le monde</a>
      </li>
     
      <li>
            <!-- search form -->
            <form action="{{route('search.product')}}" method="GET" class="form-inline" style="margin: 10px -60px 0;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control search pull-right" placeholder="Rechercher..." value="{!! request()->input('q') !!}">
                    <span class="input-group-btn">
                        <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> </button>
                    </span>
                </div>
            </form>
            <!-- /.search form -->
        </li>
</ul>