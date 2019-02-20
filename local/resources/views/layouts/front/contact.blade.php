@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    

<!--affiche top -->
 
    <!--affiche top -->

 


    <!--About us-->
   
     
<div class="container t100">
     <div class="col-md-12" align="center" style="margin-top:-5%;padding-top:3%;border-top:1px solid white">
     <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d23305.284925364882!2d-7.609045568605281!3d33.59938666545701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x3aa00134623231fe!2sBenson+Shoes!5e0!3m2!1sfr!2sma!4v1522747405160" height="500" frameborder="0" style="border:0;width:100%" allowfullscreen></iframe>
    </div>
    <div class="col-md-12" align="center" style="margin-top:-5%;padding:0">
        <div class="col-md-2"></div>     
        <div class="col-md-8" style="background-color:#fff">         
            <section class="section" style="padding:5%">
                @if(isset($message))
                        <div class="alert alert-success">
                            {{$message}}
                        </div>
                    @endif
                
                <!--Section heading-->
                <h2 class="section-heading h1 pt-4">Contactez nous</h2>
                
                <!--Section description-->
                <p class="section-description">Avez vous des questions ? S'il vous plait n'hesitez pas a nous contactez directement. Notre equipe se fera un plaisir de vous répondre.</p>
                
                <div class="row">
                    <!--Grid column-->
                    <div class="col-md-12 t100">
                            <div class="col-md-4" align="center">
                                <i class="fa fa-map-marker fa-2x "></i>
                                <p>196 Bd d’Anfa (Rond Point Porte d’Anfa), Casablanca</p>
                        </div>
                            <div class="col-md-4" align="center">
                                <i class="fa fa-phone fa-2x "></i>
                                <p>05229-49725</p>
                            </div>
                            <div class="col-md-4" align="center">
                                <i class="fa fa-envelope fa-2x "></i>
                                <p>shop@benson-shoes.com</p>
                                <p>contact@benson-shoes.com</p>
                            </div>
                        
                    </div>
                    <!--Grid column-->
                    <!--Grid column-->
                    <div class="col-md-12 b100">
                        <form id="contact-form" name="contact-form" action="{{url('/contact')}}" method="POST">
                            {{ csrf_field() }}
                            <!--Grid row-->
                            <div class="row t100">

                                <!--Grid column-->
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <label for="name" class="">Nom</label>
                                        <input type="text" id="name" name="name" class="form-control">
                                        
                                    </div>
                                </div>
                                <!--Grid column-->

                                <!--Grid column-->
                                <div class="col-md-6">
                                    <div class="md-form">

                                        <label for="email" class="">Email</label>
                                        <input type="text" id="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <!--Grid column-->

                            </div>
                            <!--Grid row-->

                            <!--Grid row-->
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="md-form">
                                        
                                        <label for="subject" class="">Sujet</label>
                                        <input type="text" id="subject" name="subject" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <!--Grid row-->

                            <!--Grid row-->
                            <div class="row ">

                                <!--Grid column-->
                                <div class="col-md-12 ">

                                    <div class="md-form">

                                        <label for="message">Message</label>
                                        <textarea type="text" id="message" name="message" rows="4" class="form-control md-textarea"></textarea>
                                    </div>

                                </div>
                            </div>
                            <!--Grid row-->

                        </form>

                        <div class="center-on-small-only pull-right">
                            <a class="btn btn-primary " onclick="document.getElementById('contact-form').submit();">Envoyer</a>
                        </div>
                        <div class="status"></div>
                    </div>
                    <!--Grid column-->

                    

                
                </div>

            </section>
        </div> 
    </div>
</div>
<!--Section: Contact v.2-->
<hr class="t100">
   
    @include('mailchimp::mailchimp')
     
@endsection
