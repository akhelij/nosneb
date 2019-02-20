
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>catalog Benson shoes</title>

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
  </head>

  <body>

    <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">Location</h4>
              <p class="text-muted"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3323.4205411863686!2d-7.598124964441299!3d33.59439139185948!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd6e5c33e3fd%3A0xd635a51b8e64a0e7!2sSte+Benson+Shoes+Sarl!5e0!3m2!1sfr!2sma!4v1536877122844" style="width:100%" height="200" frameborder="0" style="border:0" allowfullscreen></iframe></p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white">Contact</h4>
              <ul class="list-unstyled">
                <li class="text-white">Phone : 05229-49725</li>
                <li class="text-white">Email :<br> info@bensonshoes.com<br>mail@benson-shoes.com</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong>Catalog</strong>
          </a>
          <button class="navbar-toggler" id="navbar" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fa fa-phone"></span>
          </button>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Catalog Benson shoes</h1>
          <p class="lead text-muted">Discover our latest collection 2018 / 2019.</p>
          <p>
            <button  id="contact" class="btn btn-primary my-2">Contact us</button>
          </p>
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">

          <div class="row">
          <?php

$directory = "Catalog2019";
$images = glob($directory . "/*/*");
//   var_dump($images);
foreach ($images as $image) {

    ?>
            <div class="col-md-3">
              <div class="card mb-4 shadow-sm">
                <img class="card-img-top" src="<?=$image?>" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text"><strong><?=explode("/", $image)[1]?></strong><br> <?=' Article : ' . explode(".", explode("/", $image)[2])[0]?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-danger like" article="<?=' Article : ' . explode(".", explode("/", $image)[2])[0]?> with the <?=explode("/", $image)[1]?> " ><i class="fa fa-heart"></i> Like</button>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <?php }?>
          </div>
        </div>
      </div>

    </main>


<!--footer starts from here-->
<footer class="footer">
<div class="container bottom_border">
    <div class="row">
        <div class="col-md-12"  > <!--foote_bottom_ul_amrc ends here-->
        <p class="text-center mt-3 pb-1  ">Copyright 2018 | Created With <i class="fa fa-heart"> </i> by <a href="https://www.linkedin.com/in/mohamedakhelij/">Mohamed Akhelij</a></p>

        <ul class="social_footer_ul">
            <li><a href="https://www.facebook.com/BensonShoesPageOfficelle"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="https://www.instagram.com/benson_shoes/"><i class="fab fa-instagram"></i></a></li>
        </ul>
    <!--social_footer_ul ends here--></div>



    </div>
</div>
</footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script><script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script>
                $('#contact').click(function(){
                    $('#navbar').click();
                })




                function ajaxPost(url, data) {
                    var req = new XMLHttpRequest();
                    req.open("POST", url);

                    req.send(data);

                }





                $('.like').click(function(){
                    var article =  $(this).attr('article');
                    $.getJSON('http://gd.geobytes.com/GetCityDetails?callback=?', function(data) {
                        var data  = "Someone from " + JSON.parse(JSON.stringify(data, null, 2))["geobytesfqcn"] + " Like the " + article;
                        var url = "like.php";
                        ajaxPost(url, data);
                        });
                    swal("You like it!", "Thanks for you feedback!", "success");
                })

            </script>
  </body>
</html>
