<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') {{ config('app.name') }}</title>
</head>
<body>

    <img src="{{ asset('img/EmailLogo.png')}}" alt="{{ config('app.name')}}" style="display: block; width: 265px; height: 91px;" />
    <br/>

 @yield('content')

@include('emails.partials.footer')
</body>
</html>