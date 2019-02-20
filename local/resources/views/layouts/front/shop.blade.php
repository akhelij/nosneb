@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    
<hr style="margin-bottom:0">
<!--Franchise-->
    <section id="hero" class="hero-section hidden-xs" >
        <div class="row"  align="center" style="margin:0;padding:0">
             <div class="section-title b100 ">
                
                <div class="col-md-12 b100 " style="background: #fff url('images/japon.jpg') no-repeat;
            background-size: cover;
            background-position: center;
            background-position-y:100%;
            height: 800px;
            position: relative;">

                </div>
                 <div  style="background-color:#fff;position:absolute;z-index:1;top:10%;left:60%;padding:5%;width:30%">
                  <h3>Japon</h3>
                  <br>
                    ----
                    <br>
                  <p>
               5-2-10 ASAKUSA
               <br>
                TAITO-KU, TOKYO, 111-0032 JAPAN

                SUZUKI CO.,LTD. BENSON div.     
                <br>
                Tél: 03-3875-9292
                <br>
                Email: b-info@benson-shoes.jp
                
                    </p>
                 </div>
             </div>

             <div class="col-md-12">
                  
              
                 
                 <div class="col-md-7 col-sm-7  col-md-offset-2 col-sm-offset-2" >
                      <img src="{{asset('images/casablanca.jpg')}}" style="width:100%">
                  </div>
                 <div class="col-md-4 col-sm-4" style="background-color:#fff;margin-left:-20%;z-index:1;margin-top:10%;padding:5%">
                  <h3>Maroc</h3>
                  <br>
                    ----
                    <br>
                   <p>196 Bd d’Anfa (Rond Point Porte d’Anfa) - Casablanca <br>Tél : +212 (05) 22 94 97 25 </p>
                   <br>
                    ******
                    <br>
                  
                   <p>3 RDC Avenue Imam Malik el youssoufia, Soussi Label Vie - Rabat <br>Tél : +212 (05) 37 65 92 53 </p>
                    <br>
                    ******
                    <br>
                    <p>Résidence Al Tajmil place des nations - Tanger <br>Tél : +212 (05) 39 32 26 15 </p>
                 </div>
                 
              </div>
             <div class="col-md-12  t100">
                 <div class="col-md-4 col-md-offset-3 col-sm-4 col-sm-offset-3" style="background-color:#fff;margin-right:-20%;z-index:1;margin-top:10%;padding:5%">
                  <h3>Belgique</h3>
                  <br>
                    ----
                    <br>
                  <p>37, Passage Wellington. 165, chaussée de Bruxelles - 1410 
                    Waterloo  <br>  Tél : +32 (0) 25 13 04 66
                    <br>
                    ******
                    <br>
                    97, rue de Namur - 1000 Bruxelles (proximité porte de Namur - en face hotel Chambord)
                    Bruxelles <br>Tél : +32 (0) F525 02 69 59</p>
                 </div>
                 
                 <div class="col-md-5 col-sm-5" >
                      <img src="{{asset('images/belgique.jpg')}}" style="width:100%">
                  </div>
                 
             </div>
           
<div class="col-md-12 t100">
                  
              
                 
                 <div class="col-md-7 col-sm-7  col-md-offset-2 col-sm-offset-2" >
                      <img src="{{asset('images/alger.png')}}" style="width:100%">
                  </div>
                 <div class="col-md-4 col-sm-4" style="background-color:#fff;margin-left:-20%;z-index:1;margin-top:10%;padding:5%">
                  <h3>Algérie</h3>
                  <br>
                    ----
                    <br>
                   <p>65 Lotissement Djenan El Malik-Hydra.16016 - Alger <br>Tél : +213 5 58 30 36 54 </p>
                   </p>
                 </div>
                 
              </div>
              <div class="col-md-12 t100">
                 <div class="col-md-4 col-md-offset-3 col-sm-4 col-sm-offset-3" style="background-color:#fff;margin-right:-20%;z-index:1;margin-top:10%;padding:5%">
                  <h3>Côte d'ivoire</h3>
                  <br>
                    ----
                    <br>
                  <p>Avenue Chardy, Rue Gourgas 2ème sortie du cinéma les Studios Plateau
                        Abidjan - Côte d’Ivoire
                        Tél :<br>
                        (+225) 20 21 17 08<br>
                        Gsm :<br>
                        (+225) 07 89 49 21<br>
                        (+225) 07 99 15 63
                   </p>
                 </div>
                 
                 <div class="col-md-5 col-sm-5 " >
                      <img src="{{asset('images/cote-ivoire.png')}}" style="width:100%">
                  </div>
                 
             </div>

              
         </div>     
    </section>
<!--Franchise-->
 <section id="hero" class="hero-section hidden-lg hidden-md hidden-sm" >
        <div class="row"  style="margin:0;padding:0">
             <div class="col-md-12">
                
                <div class="col-xs-6" style="background: #fff url('images/japon.jpg') no-repeat;
                    background-size: cover;
                    background-position: center;
                    background-position-y:100%;
                    height: 306px;
                    position: relative;">

                </div>
                 <div  class="col-xs-6" style="background-color:#fff;padding:5%;">
                  <h3>Japon</h3>
                  <br>
                    ----
                    <br>
                        <p>
                    5-2-10 ASAKUSA
                    <br>
                        TAITO-KU, TOKYO, 111-0032 JAPAN

                        SUZUKI CO.,LTD. BENSON div.     
                        <br>
                        Tél: 03-3875-9292
                        <br>
                        Email: b-info@benson-shoes.jp
                        
                    </p>
                 </div>
             </div>
            
             <div class="col-xs-12 t100">
                 <div class="col-xs-6" style="background-color:#fff;padding:5%" height="500">
                  <h3>Belgique</h3>
                  <br>
                    ----
                    <br>
                  <p>37, Passage Wellington. 165, chaussée de Bruxelles - 1410 
                    Waterloo  <br>  Tél : +32 (0) 25 13 04 66
                    <br>
                    ******
                    <br>
                    97, rue de Namur - 1000 Bruxelles (proximité porte de Namur - en face hotel Chambord)
                    Bruxelles <br>Tél : +32 (0) F525 02 69 59</p>
                 </div>
                 
                 <div class="col-xs-6" style="background: #fff url('images/belgique.jpg') no-repeat;
                    background-size: cover;
                    background-position: center;
                    background-position-y:100%;
                    height: 500px;
                    position: relative;">
                     
                  </div>
                 
             </div>
              <div class="col-xs-12 t100">
                  
              
                 
                 <div class="col-xs-6" style="background: #fff url('images/tanger.jpg') no-repeat;
                    background-size: cover;
                    background-position: center;
                    background-position-y:100%;
                    height: 500px;
                    position: relative;">
                      
                  </div>
                 <div class="col-xs-6" style="background-color:#fff;padding:5%" height="500">
                  <h3>Maroc</h3>
                  <br>
                    ----
                    <br>
                   <p>196 Bd d’Anfa (Rond Point Porte d’Anfa) - Casablanca <br>Tél : +212 (05) 22 94 97 25 </p>
                   <br>
                    ******
                    <br>
                  
                   <p>3 RDC Avenue Imam Malik el youssoufia, Soussi Label Vie - Rabat <br>Tél : +212 (05) 37 65 92 53 </p>
                    <br>
                    ******
                    <br>
                    <p>Résidence Al Tajmil place des nations - Tanger <br>Tél : +212 (05) 39 32 26 15 </p>
                 </div>
                 
              </div>

              <div class="col-xs-12 t100">
                 <div class="col-xs-6" style="background-color:#fff;padding:5%" height="500">
                  <h3>Côte d'ivoire</h3>
                  <br>
                    ----
                    <br>
                  <p>Avenue Chardy, Rue Gourgas 2ème sortie du cinéma les Studios Plateau
                        Abidjan - Côte d’Ivoire
                        Tél :<br>
                        (+225) 20 21 17 08<br>
                        Gsm :<br>
                        (+225) 07 89 49 21<br>
                        (+225) 07 99 15 63
                   </p>
                 </div>
                 
                 <div class="col-xs-6" style="background: #fff url('images/cote-ivoire.png') no-repeat;
                    background-size: cover;
                    background-position: center;
                    background-position-y:100%;
                    height: 500px;
                    position: relative;">
                      
                  </div>
                 
             </div>
             <div class="col-xs-12 t100">
                  
              
                 
                 <div class="col-xs-6" style="background: #fff url('images/alger.png') no-repeat;
                    background-size: cover;
                    background-position: center;
                    background-position-y:100%;
                    height: 500px;
                    position: relative;">
                      
                  </div>
                 <div class="col-xs-6" style="background-color:#fff;padding:5%" height="500">
                  <h3>Algérie</h3>
                  <br>
                    ----
                    <br>
                   <p>65 Lotissement Djenan El Malik-Hydra.16016 - Alger <br>Tél : +213 5 58 30 36 54 </p>
                   </p>
                 </div>
                 
              </div>
         </div>     
    </section>
   
     

   
    @include('mailchimp::mailchimp')
     
@endsection
