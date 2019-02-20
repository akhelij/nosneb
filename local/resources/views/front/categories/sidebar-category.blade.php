
<div class="collapse navbar-collapse" align="center">
   <ul class="nav list-unstyled list-inline nav navbar-nav col-md-12">
        <li class="col-md-3 filtre"><a href="{{route('catalog')}}">Toute la collection</a></li>
    @foreach($categories as $category)
        @if($category->children()->count() > 0)

             <li class="col-md-2 filtre"  id="{{$category->slug}}" >
                  <a href="{{ route('front.category.slug', $category->slug) }}">@if($category->name == "Chaussures" ) Catégories @else {{  $category->name }} @endif <i class="fa fa-sort-down"></i></a>
           
                  @include('layouts.front.category-sidebar-sub', ['subs' => $category->children])
            </li>
        @else
            <li  class="col-md-2" @if(request()->segment(2) == $category->slug) class="active" @endif><a href="{{ route('front.category.slug', $category->slug) }}">{{ $category->name }}</a></li>
        @endif
    @endforeach
</ul>
 </div>