@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    

<!--affiche top -->
 
    <!--affiche top -->

    <style>
        .video-wrapper {position: relative; padding-bottom: 56.25%; /* 16:9 */  padding-top: 25px;}
        .video-wrapper iframe {position: absolute; top: 0; left: 0; width: 100%; height: 100%;}

    </style>


    <!--About us-->
    <section id="hero" class="hero-section " style="background:url('{{url('images/bg2.jpg')}}');background-repeat: no-repeat;
    background-size: cover;
    background-position:50%;
    background-attachment: fixed;">
        <div class="row" >
           <div class="col-md-3"></div>
            <div class="col-md-6 about"  >
                <h1 data-aos="fade-up">Benson Shoes</h1>
                <h3  data-aos="fade-up" style="color: #701306;">Depuis 1963</h3>
                <br> <br> 
                <p data-aos="fade-up">
                Une expérience vieille de plus de 50 ans. Des maîtres artisans talentueux et dotés d’un savoir faire exceptionnel. Benson Shoes est, depuis des décennies, un digne représentant du cousu Goodyear .

 

La diversité de nos modèles, la qualité de nos matières et  le confort de nos chaussants font le succès de nos collections .

 

Notre ADN :  offrir une gamme élégante et sobre allant de la chaussure classique au sneaker.</p>
                <p data-aos="fade-up">BENSON SHOES est parvenu à affermir sa position sur le marché de la chaussure grâce au succès remporté par ses modèles classiques et ses modèles sport auprès de sa cible jeune et moins jeune. La qualité et le cachet propre à la marque résultent d’un long processus de fabrication.</p>
          
                <div  id="play-video" >
                       <h4 style="cursor:pointer;padding:3%:border:2px solid black;float:right"><i class="fa fa-play-circle"></i> Regarder la vidéo</h4>
                </div>
          
            </div>
        </div>     
    </section>

     <section id="hero" class="hero-section " style="background:url('{{url('images/cuir.png')}}');background-repeat: no-repeat;
    background-size: cover;
    background-position:50%;
    background-attachment: fixed;">
        
            <div id="container-video" style="display:none">
                

                <div class="video-wrapper">
                    <iframe src="http://www.youtube.com/embed/1RUInzHz544?modestbranding=1&autoplay=1&controls=1&showinfo=0&rel=0&loop=1" id ="video" frameborder="0" allowfullscreen></iframe>
                </div>
            
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 about"  >
                    <h1 data-aos="fade-up" >L’art et la matière</h1>
                    <h3  data-aos="fade-up" style="color: #701306;">Nous collaborons avec les meilleurs tanneries du monde</h3>
                    <br> <br> 
                    <p data-aos="fade-up" >
                L’art authentique qu’exercent nos artisans est révélé grâce à la qualité irréprochable de nos matériaux. Nos cuirs, entre-autres,  proviennent des meilleures tanneries Européennes.

 

Pour assurer à nos clients une qualité irréprochable, des tests de laboratoire complets sont régulièrement opérés sur les matières principales.
                    Au Maroc, pays où la maroquinerie est un art séculaire, la marque n’a eu de cesse d’enrichir l’industrie de la chaussure grâce à ses modèles haut de gamme, à la longévité prouvée.
                            </p>
            <p data-aos="fade-up" >La recherche des meilleurs matériaux constitue l’un des soucis majeurs de la société. Aussi prospecte-t-elle régulièrement les marchés européens afin de dénicher les meilleures peausseries. Le tri des peaux est confié à des spécialistes, chargés de trouver les pièces qui répondent parfaitement aux exigences de qualité et d’hygiène notamment auprès des tanneries françaises ayant fait leurs preuves dans le tannage des peausseries de veau.</p>   </div>
         </div>     
    </section>

    <section id="hero" class="hero-section " style="background:url('{{url('images/background_aboutus.JPG')}}');background-repeat: no-repeat;
    background-size: cover;
    background-position:50%;
    background-attachment: fixed;">
        <div class="row" >
           <div class="col-md-3"></div>
            <div class="col-md-6 about" >
                <h1 data-aos="fade-up">Excellence du travail manuel</h1>
                <h3  data-aos="fade-up" style="color: #701306;">Un style unique</h3>
                <br> 
                <p data-aos="fade-up">
                        Grâce à des concepteurs dédiés à la marque, et à leurs efforts permanents pour concilier modernité du design et tradition du procédé, BENSON SHOES présente des modèles au style unique et aux lignes pures, offrant à ses clients un choix adapté à leurs exigences de distinction et de confort. 
                </p>
                <p data-aos="fade-up">La vocation première de BENSON SHOES est artisanale, et elle a fait de la finesse du travail manuel son sacerdoce. La coupe des différentes parties de la chaussure (tige, doublure semelle, etc..) est intégralement effectuée à la main. Quant aux autres étapes d’assemblage et de couture, elles nécessitent elles aussi de nombreuses interventions manuelles.


                    Par exemple, la trépointe est cousue à la tige et à la première de montage. Elle est ensuite retournée pour que l’ensemble soit cousu une deuxième fois à la semelle d’usure. Quant au remplissage de la cavité entre la semelle première et la semelle d’usure, il est assuré par un mélange à base de poudre de liège et de colle, ce qui permet aux porteurs de la marque d’apprécier une empreinte personnalisée au bout de quelques jours d’utilisation.</p>   </div>
                    </p>
          
                
          
            
        </div>     
        

       
      
    </section>
    
  
    
     

       @include('mailchimp::mailchimp')
@endsection


@section('js')
<script>
$(document).ready(function() {
    var video = $("#video")[0].src;
    $('#play-video').on('click', function(ev) {
        
      if ($('#container-video').is(':visible')) {
          $(this).children('h4').html(" <i class='fa fa-play-circle'></i> Regarder la vidéo")
          $('#container-video').hide(); 
          $("#video").attr("src","");
    } else {
          $(this).children('h4').html("<i class='fa fa-stop-circle'></i> Arrêter la vidéo");
          $('#container-video').show(); 
          $("#video").attr("src",video);
    }
    
    
    ev.preventDefault();
 
  });
});
</script>
@endsection