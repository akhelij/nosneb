<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    @include('layouts.front.category-nav')
    <ul class="nav navbar-nav navbar-right">
             <li class="visible-xs"><a href="{{ route('accounts') }}"><i class="fa fa-home"></i> Conditions d'utilisation</a></li>

        @if(auth()->check())
            <li class="visible-xs"><a href="{{ route('accounts') }}"><i class="fa fa-home"></i> Mon compte</a></li>
            <li class="visible-xs"><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Deconnexion</a></li>
        @else
            <li class="visible-xs"><a href="{{ route('login') }}"> <i class="fa fa-lock"></i> Connexion</a></li>
            <li class="visible-xs"><a href="{{ route('register') }}"> <i class="fa fa-sign-in"></i> Inscription</a></li>
        @endif
        <li id="cart" class="menubar-cart visible-xs">
            <a href="{{ route('cart.index') }}" title="View Cart" class="awemenu-icon menu-shopping-cart">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span class="cart-number">{{ $cartCount }}</span>
            </a>
        </li>
        <li class="visible-xs">
            <!-- search form -->
            <form action="{{route('search.product')}}" method="GET" class="form-inline" style="margin: 15px 0 0;">
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
</div><!-- /.navbar-collapse -->