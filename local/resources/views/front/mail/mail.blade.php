<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacter par un utilisateur</title>
</head>
<body>
    @if($errors->any())
    
    <p>Error : {{ $errors->first() }}</p>

    @endif
    @if(isset($data['message']))

    <p>{{ $data['message'] }}</p>
    @endif
</body>
</html>