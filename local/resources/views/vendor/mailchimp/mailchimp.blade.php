<section class="subscribe-section t100 b100">
    
    <div class="container">
        <div class="row">
            @if(session()->has('message'))
            <div class="col-md-8 col-md-offset-2">
                <h3 style="color:white">Honorer de vous avoir parmis nous !</h3>
                <p style="color:#93b3cb">Un code de réduction a été envoyé sur votre mail<strong> profitez-en !</strong></p>
                
                @elseif(session()->has('error'))
                <h3 style="color:white">Vous faite déjà parti de notre communauté !</h3>
                <p style="color:#93b3cb">Merci d'avoir réessayer de nous rejoindre une deuxiéme fois,<strong> Nous vous aimons aussi :)</strong></p>
                
                @else 
                <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <h3 style="color:white">Inscrivez vous à notre newsletter !</h3>
                                <p style="color:#93b3cb">Saisissez vite votre email et bénéficiez d'une réduction de 5% sur tous vos achats en ligne<strong> sur le champ!</strong></p>
                                <form action="{{route('store_newsletter')}}" class="form-inline form-field-section" method="post">
                                    {{csrf_field()}}
                                    <input type="email" name="email" class="newsletter-input subscribe-form-control" placeholder="Votre email" value="" style="border-top: 1px solid #273959;
                                    border-right: 1px solid #273959;
                                    border-left: 1px solid #273959;
                                    background:transparent;
                                    border-bottom: 1px solid #fff;
                                    text-align: left;
                                    border-radius:0px;
                                    ">
                                                    <button type="submit" class="btn btn-subscribe" style="    background: transparent;
                                    border-bottom: 1px solid white;
                                    border-left: 1px solid white;
                                    border-right: 1px solid white;
                                    border-radius: 0px;
                                    margin-left: -7px;
                                    margin-top: 1px;">Inscription</button>
                                </form>
                            </div>
                        </div>
                    </div>     
                    @endif
            </div>
        </div>
    </div>
</section>