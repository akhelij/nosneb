
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail Verification</title>
</head>
<body>
    # Une derniere étape 
Nous avons juste besoin de confirmer votre adresse mail pour prouver que vous êtes un humain.
    <br>
    <a href="{{url('/register/confirm/'.$data->remember_token)}}">Confirmer l'email</a> <br>
    
Merci à vous,<br>
{{ config('app.name') }}
</body>
</html>