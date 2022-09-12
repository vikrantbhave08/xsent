<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>

    <p>{{ $details['body'] }}</p><br>
    
    @if($details['username'])
    <p>Username : {{ $details['username'] }}</p><br>
    <p>Password : {{ $details['password'] }}</p><br>
    @endif
   
    <p>Thank you</p>
</body>
</html>