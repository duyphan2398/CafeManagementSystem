<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Cafe-Management-System</title>
</head>
<body>

<h1>Username: {{$details['username']}}</h1>
<h3>Click here to reset password and change your information: </h3>
<a href="{{$details['link']}}">{{$details['link']}}</a>
</body>
</html>
