<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>

    <p>{{ $details['body'] }}</p><br>
    
    @if($details['username'])
    <p>Username : {{ $details['username'] }}</p>
    <p>Password : {{ $details['password'] }}</p>
    @endif
   
    <p>Thank you</p>
</body>
</html>