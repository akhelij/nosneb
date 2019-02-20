

    <footer class="footer-section footer">
    <div class="container">
        <div class="row">
            <div class="col-md-2" style="padding:1%;margin-top:-2%">
                <script language="JavaScript" type="text/javascript">
                        TrustLogo("https://www.benson-shoes.com/images/comodo_secure.png", "SC7", "none");
                    </script>
                    <a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL EV</a>
           
            </div>
            <div class="col-md-8 text-center">
        
           
               <div >  
                    <ul class="footer-menu" >
                        <li> <a href="{{ route('accounts') }}">Votre compte</a>  </li>
                        <li> <a href="{{ route('contact') }}">Contactez nous</a>  </li>
                        <li> <a href="{{ url('terms-of-use') }}">Conditions d'utilisation</a>  </li>
                    </ul>

                    <ul class="footer-social" >
                        <li> <a href="https://www.facebook.com/BensonShoesPageOfficelle/"> <i class="fa fa-facebook" aria-hidden="true"></i>  </a> </li>
                        <li> <a href="https://twitter.com/bensonshoes"> <i class="fa fa-twitter" aria-hidden="true"></i>   </a> </li>
                        <li> <a href="https://www.instagram.com/benson_shoes/"> <i class="fa fa-instagram" aria-hidden="true"></i>  </a> </li>
                    </ul>

                    <p >&copy; <a href="{{ config('app.url') }}">{{ config('app.name') }}</a> | All Rights Reserved</p>
                </div> 

            
            </div>
             <div class="col-md-2">
              <img src="{{url('images/Fpay_paiement_3.png')}}" alt="" class="pull-right hidden-xs" width="150" style="padding:1%;">
            </div>
        </div>
    </div>
</footer>

<!-- Include all compiled plugins (below), or include individual files as needed -->

<script src="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.js"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/custom.js') }}"></script>
<script src="{{ asset('front/js/owl.carousel.min.js') }}"></script>
 <script>
    AOS.init();
  </script>