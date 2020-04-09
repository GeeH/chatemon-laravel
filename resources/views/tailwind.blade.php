<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;700;900&display=swap" rel="stylesheet">
    <title>Temp UI for Chatemon</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-screen antialiased leading-none bg-scroll overflow-hidden font-sans"
      style="background-image: url('{{ asset('img/taff.jpg') }}');
          background-size: cover; background-position: center;">

<div id="index"></div>

<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</body>
</html>
