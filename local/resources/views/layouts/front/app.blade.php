<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-9325492-23"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env('GOOGLE_ANALYTICS') }}');
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Chaussures haut de gamme pour homme, Benson Shoes est, depuis des décennies, un digne représentant du cousu Goodyear . La diversité de nos modèles, la qualité de nos matières et le confort de nos chaussants font le succès de nos collections">
    <meta name="keywords" content="Chaussures haut de gamme, chaussures anglaises, richelieus, mocassin, derby, sneaker, espadrille, chassures classe, benson, bensonshoes, men's oxfords shoes, women's oxfords shoes, dress shoes, monk shoes, english shoes, benson shoes, luxury shoes">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/style.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ asset('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js')}}"></script>
    <script src="{{ asset('https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
    <link rel="icon" sizes="57x57" href="{{ asset('favicons/logo2.png')}}">
    @yield('css')

    <link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">
    <meta property="og:url" content="{{ request()->url() }}"/>
    @yield('og')
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script type="text/javascript"> //<![CDATA[
var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.comodo.com/" : "https://www.trustlogo.com/");
document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script>
</head>
<body class="boxed"  style="background: #94b3ca;overflow-x:hidden">
    <div class="container" style="background: #283a59">
    <noscript>
        <p class="alert alert-danger">
            You need to turn on your javascript. Some functionality will not work if this is disabled.
            <a href="https://www.enable-javascript.com/" target="_blank">Read more</a>
        </p>
    </noscript>

    <section>
        <div class="row hidden-xs" style="margin:0">
            <div style="display: block;
                padding: 6px 20px;
                font-size: 14px;
                line-height: 18px;
                color: #fff;
                background: #182134;
                height: 35px;">

                <div class="pull-left">
                    <ul class="mini-header nav navbar-nav navbar-left ">
                        <li>
                            Besoin d'aide ?
                        </li>
                        <li>
                            +212 522 464 140
                        </li>


                    </ul>
                </div>
                @if(Auth::check() && Auth::user()->email_verified_at == 0)
                 <div class="alert alert-warning alertApp" style="position:absolute;left:37%;opacity:0.5" >Veuillez procéder à la vérification de votre mail : {{Auth::user()->email}}</div>
                @elseif(Auth::check() && Auth::user()->status == 0)
                <div class="alert alert-warning alertApp" style="position:absolute;left:37%;opacity:0.5" >Votre compte à été désactiver contactez nous pour plus d'informations </div>
               @endif

                <div class="pull-right">
                    <ul class="mini-header nav navbar-nav navbar-right">
                         <li><a href="{{ route('accounts') }}"><i class="fa fa-phone"></i> Contactez nous</a></li>
                        @if(auth()->check())
                            <li><a href="{{ route('accounts') }}"><i class="fa fa-home"></i> Mon compte</a></li>
                            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Deconnexion</a></li>
                        @else
                            <li><a href="{{ route('login') }}"> <i class="fa fa-lock"></i> Connexion</a></li>
                            <li><a href="{{ route('register') }}"> <i class="fa fa-sign-in"></i> Inscription</a></li>
                        @endif
                        <li class="menubar-cart hidden" >
                            <select name="currency" id="currency" class="mt10">
                                <option @if(Session::has('currency') && Session::get('currency') == "MAD") selected @endif>MAD</option>
                                <option @if(Session::has('currency') && Session::get('currency') == "USD") selected @endif>USD</option>
                                <option @if(Session::has('currency') && Session::get('currency') == "EUR") selected @endif>EUR</option>
                            </select>
                        </li>
                        <li id="cart" class="menubar-cart">

                            <a href="{{ route('cart.index') }}" title="View Cart" class="awemenu-icon menu-shopping-cart">
                                <i class="fa fa-shopping-cart " style="color:grey" aria-hidden="true"></i>
                                <span class="cart-number">{{ $cartCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <header id="header-section" >
            <div class="col-md-12 logo-head" align="center">
                            <a href="{{ route('home') }}"><img src="{{asset('images/logo.png')}}" alt="" ></a>
             </div>
            <nav class="navbar navbar-default">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header col-md-2">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>


                    </div>
                    <div class="col-md-12" style="margin-top:1%;text-align:center">

                        <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"  >
                                        <ul class="list-unstyled list-inline nav navbar-nav col-md-12"  >
                                                <li class="col-md-2">
                                                    <a href="{{url('/')}}">Accueil</a>
                                                </li>
                                                <li class="col-md-2">
                                                    <a href="{{url('/about')}}">Héritage</a>
                                                </li>
                                                @foreach($categories as $category)
                                                    @if($category->name != "Formes" && $category->name != "Spéciale" && $category->name != "Benson Blue Note" )
                                                    <li class="col-md-2">
                                                        @if($category->children()->count() > 0)
                                                            @include('layouts.front.category-sub', ['subs' => $category->children])
                                                        @else
                                                            <a @if(request()->segment(2) == $category->slug) class="active" @endif href="{{route('front.category.slug', $category->slug)}}">{{$category->name}} </a>
                                                        @endif
                                                    </li>
                                                    @endif
                                                @endforeach
                                                <li class="col-md-2 col-xs-mt-5" >
                                                    <a href="{{url('/shop')}}">Benson dans le monde</a>
                                                </li>
                                                
                                                <li class="col-md-2 hidden-lg hidden-md hidden-sm hidden-xs">
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
                                        <!-- search form
                                        <form action="{{route('search.product')}}" method="GET" class="form-inline" style="margin: 15px 0 0;">
                                            <div class="input-group">
                                                <input type="text" name="q" class="form-control search pull-right" placeholder="Rechercher..." value="{!! request()->input('q') !!}">
                                                <span class="input-group-btn">
                                                    <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> </button>
                                                </span>
                                            </div>
                                        </form>
                                        /.search form -->
                                    </li>
                                </ul>
                            </div><!-- /.navbar-collapse -->
                    </div>
                </div>
            </nav>
        </header>
    </section>
    @yield('content')

    @include('layouts.front.footer')

    
    
 
    </div>

       <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-127885993-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'AW-775985184');


        $('#currency').on('change', function(){

            $.ajax({
            url : 'https://www.benson-shoes.com/changeCurrency',
            type : 'POST',
            data : {"_token": "{{ csrf_token() }}","currency" : $(this).val()},
            success : function(response){ // code_html contient le HTML renvoyé
                location.reload();
            }

            });

        })

        setTimeout(function () {
            $('.alertApp').hide(500);
        },10000);
        $('.filtre').hover(function(){

            var indice = $(this).attr('id');
            $('#sub'+indice).show();
        }, function(){
            // change to any color that was previously used.
            var indice = $(this).attr('id');
            $('#sub'+indice).hide();
        })

        $("#shop").click(function(event){
            
            $("#linkToShop").click(); //opens contact form
        });
</script>

    @yield('js')  

      
</body>
</html>
