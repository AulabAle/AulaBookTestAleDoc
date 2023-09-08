<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Make {{$user->email}} revisor</title>
</head>
<body>
    <h1>Un utente ha richiesto di lavorare con noi</h1>
    <h2>I suoi dati:</h2>
    <p>Nome: {{$user->name}}</p>
    <p>Email: {{$user->email}}</p>
    <p>Rendilo revisore:</p>
    <a href="{{route('make.revisor', ['user' => $user->id , 'cryptedId' => $cryptedId])}}">Rendi revisore</a>
</body>
</html>