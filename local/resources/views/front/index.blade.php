@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators 
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
  </ol>
-->
  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
      
        <a href="{{url('shop')}}" >
            <div class="item active"  style="background-image: url('images/slide2.png'); ">
                
                    <div class="carousel-caption" >
                    

                    </div>
                
            </div>
        </a>
    
  </div>

  <!-- Controls -->
  
</div>

<!--affiche top -->
 
    <!--affiche top -->

    <!--Featured -->
    @if(!is_null($features))
        <div class="container mt-10" >
            @include('front.products.product-list', ['products' => $features,'currency' => $currency,'currency_diff' => $currency_diff])
           
        </div>
    @endif
    <!--featured -->


    <!--About us-->
   
        <div class="container mt-10" style="background:url('{{url('images/background_aboutus.JPG')}}');background-repeat: no-repeat;
    background-size: cover;
    background-position:50%;
    background-attachment: fixed;">
             <div class="col-md-12 b100">
                  <div class="section-title t100 b100" align="center">
                   
                            <a href="{{ route('home') }}"><img src="{{asset('images/logo.png')}}" alt="" ></a>

                
                  </div>
                <div class="col-lg-6 col-md-12 col-sm-12 hidden-xs" style="text-align:center">
                    <iframe width="600" height="350" src="https://www.youtube.com/embed/1RUInzHz544" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style="padding:0; border:2px solid white"></iframe>
                </div>
                 <div class="col-lg-5 col-md-12 col-sm-12" >
                      <h3 data-aos="fade-up" style="color: #701306;">Depuis 1963</h3>
                        <p data-aos="fade-up" style="color: white;text-align:justify"> 
                            
                        Une expérience vieille de plus de 50 ans. Des maîtres artisans talentueux et dotés d’un savoir faire exceptionnel. Benson Shoes est, depuis des décennies, un digne représentant du cousu Goodyear .

 

La diversité de nos modèles, la qualité de nos matières et  le confort de nos chaussants font le succès de nos collections .

 

Notre ADN :  offrir une gamme élégante et sobre allant de la chaussure classique au sneaker.
                        </p>
                        <p class ="hidden-xs" data-aos="fade-up"  style="color: white;text-align:justify">BENSON SHOES est parvenu à affermir sa position sur le marché de la chaussure grâce au succès remporté par ses modèles classiques et ses modèles sport auprès de sa cible jeune et moins jeune. La qualité et le cachet propre à la marque résultent d’un long processus de fabrication.</p>
                    </div>
                
             </div>
         </div> 
    <!--About us-->
    <div class="row  hidden-xs hidden-sm" style="margin:0px">
        <div class="col-md-6  " style="
background: #795a3b;
;text-align:center;padding:5%;min-height:280px;height:500px">
             <div class="section-title" style="color:#273959;" data-aos="fade-left">
                <h2 style="color:#273959">Magasins et Franchises</h2>
            </div>
            <div class="col-md-12"  data-aos="fade-right">
                <h4 style="color:white;margin-top:5%;margin-bottom:5%;line-height:3">Nous comptons 6 magasins et franchises dans le monde,<br>  et la conquête n'est pas encore terminée.</h4>
            </div>
            <div class="col-md-4"></div>
        <div class="col-md-4" style="border:2px solid #273959;text-align:center;" data-aos="fade-up"><a href="{{route('shop')}}"><h4 style="color:white;margin-top:5%">Voir plus</h4></a></div>
        </div>
        <div class="col-md-6" style="min-height:280px;height:500px;padding: 0;">
            <div id="carousel-example-generic" class="carousel2 slide" data-ride="carousel" >
  <!-- Indicators -->
               

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active" style="background-image: url('images/slide-magasin1.JPG');
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position:50%; ">
                        <div class="carousel-caption" >
                        
                        </div>
                    </div>
                    <div class="item " style="background-image: url('images/slide-magasin2.JPG');
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position:50%; ">
                        <div class="carousel-caption" >
                            
                        </div>
                    </div>
                    
                </div>

                <!-- Controls -->
                
                </div>
        </div>
    </div>
    <!--Categories
    <div class='row'>
    <div class="col-md-12">
        <div class="section-title b100 t100">
                <h2>Choisissez le style qui vous convient !</h2>
        </div>
        <div class="col-md-5 col-md-offset-1 " data-aos="zoom-up">
            <img src="images/monk.jpg" class="bordered"> 
            <div class="col-md-8 category " >
                <h3>Boucles</h3>
                <a href="http://www.benson-shoes.net/ecomerce/public/category/boucles">Voir plus</a>
            </div>
        </div>
        <div class="col-md-5 col-md-offset-1 "  data-aos="zoom-up">
            <img src="images/loafers.jpg" class="bordered">
             <div class="col-md-8 category" >
                <h3>Mocassins</h3>
                <a href="http://www.benson-shoes.net/ecomerce/public/category/mocassins">Voir plus</a>
            </div>
        </div>
         <div class="col-md-5 col-md-offset-1 "  data-aos="zoom-up">
            <img src="images/derbies.jpg" class="bordered">
             <div class="col-md-8 category" >
                <h3>Richelieus</h3>
                <a href="http://www.benson-shoes.net/ecomerce/public/category/richelieus">Voir plus</a>
            </div>
        </div>
        <div class="col-md-5 col-md-offset-1 "  data-aos="zoom-up">
            <img src="images/sneakers.jpg" class="bordered">
             <div class="col-md-8 category" >
                <h3>Sneakers</h3>
                <a href="http://www.benson-shoes.net/ecomerce/public/category/sneakers">Voir plus</a>
            </div>
        </div>
         <div class="col-md-5 col-md-offset-1"  data-aos="zoom-up">
            <img src="images/oxford.jpg" class="bordered">
             <div class="col-md-8 category" >
                <h3>Derbys</h3>
                <a href="http://www.benson-shoes.net/ecomerce/public/category/derbys">Voir plus</a>
            </div>
        </div>
        <div class="col-md-5 col-md-offset-1"  data-aos="zoom-up">
            <img src="images/boots.jpg" class="bordered">
             <div class="col-md-8 category" >
                <h3>Bottines</h3>
                <a href="http://www.benson-shoes.net/ecomerce/public/category/bottes-bottines">Voir plus</a>
            </div>
        </div>
    </div>
</div>
<!--Categories-->
     

     <!--Affiche bottom -->
    
     <div class='row hero-section ' style="margin: 0px;background-repeat: no-repeat;
    ">
       <!-- <div id="browse-accessories-btn"> <a class="btn btn-default browse-all-btn" href="{{ route('front.category.slug', $accessories->slug) }}" role="button">Decouvrez Nos accessoires</a></div>
       -->
       
             <div class="col-md-6" style="margin:0px;padding: 0;min-height:280px;height:500px">
                    <img src="{{url('img/reparation.png')}}" style="width:100%;height:500px">
            </div>
       
             <div class="col-md-6  " style="background: #795a3b;text-align:center;padding:5%;min-height:280px;height:500px">
             <div class="section-title" style="color:#273959;" data-aos="fade-left">
                <h2 style="color:#273959">Entretien et réparation</h2>
            </div>
            <div class="col-md-12"  data-aos="fade-right">
                <h4 style="color:white;margin-top:5%;margin-bottom:5%;line-height:3">Pour l'entretien et la réparation de vos chaussures Benson, nos magasins de Casablanca et Rabat disposent à votre service d'un atelier de glaçage et de patine.</h4>
            </div>
            <div class="col-md-4"></div>
        <div class="col-md-4" style="border:2px solid #273959;text-align:center;" data-aos="fade-up"><a href="{{route('maintain')}}"><h4 style="color:white;margin-top:5%">Voir plus</h4></a></div>
        </div>
        
    </div>
    <!--Affiche bottom -->  
<!--Accessories-->
<div class='row hero-section hidden-md hidden-sm hidden-xs' style="margin:0px;">
    <div class="col-md-4 hidden-xs hidden-sm nopadding" style="background:url('{{url('images/leftSocial.png')}}');background-repeat: no-repeat;
    background-size: 40%;
    background-position: 0%;
    background-attachment: fixed;
    min-height: 250px;
    height: 500px"></div>
    <div class="col-md-4 hidden-xs col-sm-12 nopadding " style="background:url('{{url('images/middleSocial.png')}}');background-repeat: no-repeat;
    background-size: 35%;
    background-position:50%;
    background-attachment: fixed;
    min-height:250px;
    height:500px">
        <div style="position:absolute;top:40%;left:25%">
            <h1 style="color:#fff"> Rejoignez nous sur :</h1>
            <a href="https://www.facebook.com/BensonShoesPageOfficelle/"><i class="facebook" ><img src="{{url('images/facebook.png')}}" alt="" width="65"></i></a>
            <a href="https://www.instagram.com/benson_shoes/"><i class="instagram pull-right" ><img src="{{url('images/instagram.png')}}" alt="" width="75"></i></a>
        </div>
    </div>
    <div class="col-md-4 hidden-xs hidden-sm nopadding rightsocial" style="background:url('{{url('images/rightSocial.png')}}');background-repeat: no-repeat;
    background-size: 35%;
    background-position:98%;
    background-attachment: fixed;
     min-height:250px;
    height:500px"></div>
    

</div>
<!--Accessories-->      
  
    @include('mailchimp::mailchimp')
     
@endsection

