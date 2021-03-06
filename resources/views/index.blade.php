<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Somalet - app</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Inject our style which converted from SASS => CSS --}}
    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.css">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/webfonts/fa-brands-400.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css">
   <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

</head>
<body>


{{-- Where our app will mounted --}}
<div id="app"></div>

{{--inject Scripte--}}
<script src="{{mix('/js/app.js')}}"></script>

</body>
</html>
