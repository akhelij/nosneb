@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="maintain"/>
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
                <h1 data-aos="fade-up">Entretien et réparation</h1>
                  <h3  data-aos="fade-up" style="color: #701306;">Atelier d'entretien et bar à patine</h3>
                  <br><br>
                <p data-aos="fade-up">
                 Pour l'entretien et la réparation de vos chaussures Benson, nos magasins de Casablanca et Rabat disposent d'un atelier de glaçage et de patine.
                </p>
                <p data-aos="fade-up">
                 Vous pouvez nous laisser vos chaussures usagées afin de leur redonner brillance et éclat.
                </p>
                <br>
                 <h3>Pour un entretien du cuir, notre glaçage de renommée fera votre bonheur.</h3>
                 <p> Vous nous laisserez une paire vieillissante et vous récupérerez des chaussures que vous aurez à nouveau envie de porter. Le cuir sera décapé, nourri, traité puis lustré de manière à obtenir une belle brillance à l'arrière et au bout de vos chaussures. </p>
<br><h3>Concernant la patine, il s'agit de la coloration manuelle de vos paires neuves et moins neuves.</h3>
<p>  Une belle couleur nuancée sera obtenue. Ces paires personnalisées pour vous sont uniques. Le travail manuel donne à chacune une touche différente.</p>
<br><h3>Quand vos semelles ou talons sont usés, le bon réflexe à avoir est de faire appel à l'un de nos ateliers. </h3>
<p>Une paire bien portée est toujours d'un extreme confort . 
    Il est ainsi très intéressant de lui donner une seconde vie. 
    La semelle sera entièrement décousue et retirée. Votre paire sera montée sur nouvelle semelle de votre choix. Ce travail est suivi d'un entretien du cuir; vous aurez du mal à reconnaître vos chaussures !!</p>
    <br><h4>N'hesitez pas à nous rendre visite, nos conseillers sont toujours à votre disposition pour répondre à vos demandes. Nous réalisons pour vous de belles collections, nous vous conseillons pour vos achats, et nous tenons à vous aider à garder vos chaussures dans un bel état et à faire vivre vos cuirs le plus longtemps possible.</h4>
                
          
            </div>
        </div>     
    </section>


  
    
     

       @include('mailchimp::mailchimp')
@endsection

