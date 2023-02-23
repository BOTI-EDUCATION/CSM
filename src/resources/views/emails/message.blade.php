<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>
<body>
    <h2>From: {{$details['type']}}</h2>
    <hr>
    <p><b>Name</b>: {{ $details['name'] }}</p>
    <p><b>Email</b>: {{ $details['email'] }}</p>
    <p><b>Tel</b>: {{ $details['tel'] }}</p>
    <p><b>City</b>: {{ $details['ville'] }}</p>
    <p><b>School</b>: {{ $details['ecole'] }}</p>
    <p><b>Student Numbers</b>: {{ $details['nombre'] }}</p>
    <p><b>Levels</b>: {{ $details['niveau'] }}</p>
</body>
</html>